"""Merge targeted Artifact Tool changes into the original Excel package.

Usage: python merge.py ORIGINAL STAGE CHANGES_JSON OUTPUT
"""
from copy import deepcopy
import json
import re
import sys
from pathlib import Path
from zipfile import ZIP_DEFLATED, ZipFile
from lxml import etree

S = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main'
q = lambda tag: f'{{{S}}}{tag}'

def equal_xml(a, b):
    return (a.tag, dict(a.attrib), (a.text or '').strip(), [equal_xml(x, y) for x, y in zip(a, b)]) if len(a) == len(b) else None

def signature(node):
    return node.tag, tuple(sorted(node.attrib.items())), (node.text or '').strip(), tuple(signature(c) for c in node)

def read(zipfile, name):
    return etree.fromstring(zipfile.read(name))

def xml(node):
    return etree.tostring(node, encoding='UTF-8', xml_declaration=True, standalone=True)

def column(address):
    value = 0
    for letter in re.match(r'[A-Z]+', address).group():
        value = value * 26 + ord(letter) - 64
    return value

def replace(parent, name, new):
    old = parent.find(q(name))
    if old is None:
        return
    if new is None:
        parent.remove(old)
    else:
        parent.replace(old, deepcopy(new))

def merge_styles(source, stage):
    # Preserve original style elements and retain all existing indices.
    for name in ('numFmts','fonts','fills','borders','cellStyleXfs','cellXfs'):
        a, b = source.find(q(name)), stage.find(q(name))
        if a is None or b is None:
            if a is not None or b is not None:
                raise ValueError(f'No se pudo comparar {name}')
            continue
        if len(b) < len(a):
            raise ValueError(f'El catálogo de estilos {name} se redujo')
        # Artifact export sometimes drops Excel-only font/fill details. Existing
        # indices are deliberately resolved against the unchanged original.
        if name in ('cellStyleXfs','cellXfs','borders','numFmts'):
            for index, item in enumerate(a):
                if signature(item) != signature(b[index]):
                    raise ValueError(f'Cambió el índice {index} de {name}')
        for item in list(b)[len(a):]:
            a.append(deepcopy(item))
        if 'count' in a.attrib:
            a.set('count', str(len(a)))
    return source

def ensure_row(data, index):
    result = data.find(f"{q('row')}[@r='{index}']")
    if result is not None:
        return result
    result = etree.Element(q('row'), r=str(index))
    following = next((r for r in data if int(r.get('r')) > index), None)
    if following is None:
        data.append(result)
    else:
        following.addprevious(result)
    return result

def transfer_cell(original_data, stage_data, address, stage_strings):
    row_number = int(re.search(r'\d+', address).group())
    source_row = stage_data.find(f"{q('row')}[@r='{row_number}']")
    source_cell = source_row.find(f"{q('c')}[@r='{address}']") if source_row is not None else None
    row = ensure_row(original_data, row_number)
    existing = row.find(f"{q('c')}[@r='{address}']")
    if source_cell is None:
        if existing is not None:
            for child in list(existing):
                if child.tag in (q('f'),q('v'),q('is')):
                    existing.remove(child)
            existing.attrib.pop('t',None)
        return
    cell = deepcopy(source_cell)
    if cell.get('t') == 's':
        value = cell.find(q('v'))
        shared_item = stage_strings[int(value.text)]
        cell.remove(value)
        cell.set('t','inlineStr')
        inline = etree.SubElement(cell,q('is'))
        for item in shared_item:
            inline.append(deepcopy(item))
    elif cell.get('t') == 'str' and cell.find(q('f')) is None:
        old = cell.find(q('v'))
        value = old.text or '' if old is not None else ''
        if old is not None:
            cell.remove(old)
        cell.set('t','inlineStr')
        text = etree.SubElement(etree.SubElement(cell,q('is')),q('t'))
        text.text = value
    if existing is not None:
        row.replace(existing,cell)
    else:
        after = next((c for c in row if c.tag == q('c') and column(c.get('r')) > column(address)),None)
        if after is None:
            row.append(cell)
        else:
            after.addprevious(cell)

def merge_sheet(original, staged, number, edits, formats, heights, stage_strings):
    a,b = original.find(q('sheetData')),staged.find(q('sheetData'))
    addresses = set(edits) | set(formats)
    if number == 1:
        for rownum in range(31,35):
            addresses.update(f'{c}{rownum}' for c in 'ABCDEFGHIJKLMNOPQ')
    if number == 7:
        for head in range(10,111,20):
            for rownum in (head-2,head-1):
                addresses.update(f'{chr(64+c) if c<=26 else "A"+chr(64+c-26)}{rownum}' for c in range(4,52))
    for address in sorted(addresses, key=lambda x:(int(re.search(r'\d+',x).group()),column(x))):
        transfer_cell(a,b,address,stage_strings)
    for rownum in heights:
        newrow=b.find(f"{q('row')}[@r='{rownum}']")
        row=ensure_row(a,rownum)
        if newrow is not None:
            for key in ('ht','customHeight'):
                if key in newrow.attrib:
                    row.set(key,newrow.get(key))
    if number == 4:
        cols=original.find(q('cols'))
        newcols=staged.find(q('cols'))
        old=next(x for x in cols if int(x.get('min'))==3 and int(x.get('max'))==3)
        new=next(x for x in newcols if int(x.get('min'))==3 and int(x.get('max'))==3)
        cols.replace(old,deepcopy(new))
    if number == 7:
        cols=original.find(q('cols'))
        newcols=staged.find(q('cols'))
        for col in newcols:
            if int(col.get('min')) >= 30:
                cols.append(deepcopy(col))
        replace(original,'mergeCells',staged.find(q('mergeCells')))
        dimension=original.find(q('dimension'))
        if dimension is not None:
            dimension.set('ref','A1:AY164')
    if number == 8:
        dimension=original.find(q('dimension'))
        if dimension is not None:
            dimension.set('ref','A1:K59')
    return original

def main(source_path,stage_path,change_path,output_path):
    manifest=json.loads(Path(change_path).read_text(encoding='utf-8'))
    changes=manifest.get('changes',manifest)
    formats=manifest.get('formats',{})
    heights=manifest.get('heights',{})
    output=Path(output_path)
    output.parent.mkdir(parents=True,exist_ok=True)
    with ZipFile(source_path) as source, ZipFile(stage_path) as stage:
        original_names=[x.get('name') for x in read(source,'xl/workbook.xml').find(q('sheets'))]
        stage_names=[x.get('name') for x in read(stage,'xl/workbook.xml').find(q('sheets'))]
        if original_names != stage_names:
            raise ValueError('El orden o nombre de hojas cambió')
        stage_strings=list(read(stage,'xl/sharedStrings.xml'))
        replacements={'xl/styles.xml':xml(merge_styles(read(source,'xl/styles.xml'),read(stage,'xl/styles.xml')))}
        for sheet, edits in changes.items():
            number=int(sheet)
            part=f'xl/worksheets/sheet{number}.xml'
            replacements[part]=xml(merge_sheet(read(source,part),read(stage,part),number,edits,formats.get(sheet,[]),heights.get(sheet,[]),stage_strings))
        # Rebuild the calculation chain on opening, avoiding stale dependencies.
        rels=etree.fromstring(source.read('xl/_rels/workbook.xml.rels'))
        for child in list(rels):
            if child.get('Type','').endswith('/calcChain'):
                rels.remove(child)
        replacements['xl/_rels/workbook.xml.rels']=xml(rels)
        types=etree.fromstring(source.read('[Content_Types].xml'))
        for child in list(types):
            if child.get('PartName')=='/xl/calcChain.xml':
                types.remove(child)
        replacements['[Content_Types].xml']=xml(types)
        book=read(source,'xl/workbook.xml')
        calc=book.find(q('calcPr'))
        if calc is not None:
            calc.set('fullCalcOnLoad','1')
        replacements['xl/workbook.xml']=xml(book)
        with ZipFile(output,'w',compression=ZIP_DEFLATED) as out:
            for item in source.infolist():
                if item.filename == 'xl/calcChain.xml':
                    continue
                out.writestr(item,replacements.get(item.filename,source.read(item.filename)))
    with ZipFile(source_path) as source, ZipFile(output) as out:
        if out.testzip() is not None:
            raise ValueError('Error en archivo ZIP')
        for num in [5,6,9,10,11,12]:
            part=f'xl/worksheets/sheet{num}.xml'
            if part not in replacements and source.read(part) != out.read(part):
                raise ValueError(f'Hoja {num} modificada')
    print('Mezcla verificada, con hojas ajenas conservadas.')

if __name__=='__main__':
    if len(sys.argv)!=5:
        raise SystemExit('Usage: merge.py ORIGINAL STAGE CHANGES_JSON OUTPUT')
    main(*sys.argv[1:])
