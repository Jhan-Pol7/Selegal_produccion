import fs from 'node:fs/promises';
import path from 'node:path';
import {createRequire} from 'node:module';
import {pathToFileURL} from 'node:url';

const [dependencies,source,work]=process.argv.slice(2);
const require=createRequire(import.meta.url);
const {FileBlob,SpreadsheetFile}=await import(pathToFileURL(require.resolve('@oai/artifact-tool',{paths:[dependencies]})));
const inputs=JSON.parse(await fs.readFile(path.join(work,'data.json'),'utf8'));
const workbook=await SpreadsheetFile.importXlsx(await FileBlob.load(source));
const functional=workbook.worksheets.getItemAt(0);
const nonfunctional=workbook.worksheets.getItemAt(1);
const matrix=workbook.worksheets.getItemAt(6);
const changes={'1':{},'2':{},'7':{}};
const write=(sheet,number,address,value)=>{
 changes[String(number)][address]=value;
 if(value===null)sheet.getRange(address).clear({applyTo:'contents'});
 else sheet.getRange(address).values=[[value]];
};
const column=(i)=>{
 let a='';
 for(let n=i;n>0;n=Math.floor((n-1)/26))a=String.fromCharCode(65+(n-1)%26)+a;
 return a;
};

// Copiar el formato de la última fila existente a los nuevos requisitos.
for(let index=0;index<inputs.newRf.length;index++){
 const row=31+index;
 for(const col of 'BCDEFGHIJKLMNOQ')functional.getRange(`${col}${row}`).copyFrom(functional.getRange(`${col}30`),'all');
 if(row!==34)functional.getRange(`A${row}`).copyFrom(functional.getRange('A30'),'all');
 functional.getRange(`P${row}`).copyFrom(functional.getRange('P30'),'all');
 const values=inputs.newRf[index];
 for(let j=0;j<values.length;j++)write(functional,1,`${'ABCDEF'[j]}${row}`,values[j]);
 for(const col of 'GHIJKLMNO')write(functional,1,`${col}${row}`,col==='H'?1:2);
 write(functional,1,`Q${row}`,'Por definir');
 functional.getRange(`P${row}`).formulas=[[`=SUM(G${row}:O${row})`]];
 functional.getRange(`A${row}`).format.rowHeight=36;
 functional.getRange(`B${row}:F${row}`).format.wrapText=true;
}
const baseCodes=[[5,1],[6,2],[9,3],[10,4],[12,5],[16,6],[17,7],[21,8],[22,9],[25,10],[26,11],[30,12],[31,13],[32,14],[33,15]];
for(const [row,num] of baseCodes)write(nonfunctional,2,`B${row}`,`RNF${String(num).padStart(2,'0')}`);
nonfunctional.getRange('C17').format.wrapText=true;
nonfunctional.getRange('A17').format.rowHeight=25;
for(const row of [13,18])for(const col of 'BC')write(nonfunctional,2,`${col}${row}`,null);
for(const [row,id,description] of inputs.rnf.filter(x=>[7,27,28].includes(x[0]))){
 for(const col of 'BC')nonfunctional.getRange(`${col}${row}`).copyFrom(nonfunctional.getRange(`${col}26`),'all');
 write(nonfunctional,2,`B${row}`,id);
 write(nonfunctional,2,`C${row}`,description);
 nonfunctional.getRange(`C${row}`).format.wrapText=true;
 nonfunctional.getRange(`A${row}`).format.rowHeight=32;
}

write(matrix,7,'A7','X = relación HU–RF en la fase; no acredita su ejecución. RF26–RF29 no tienen HU de Miro asociada.');
for(let phase=0;phase<6;phase++){
 const header=10+phase*20;
 for(const band of [header-2,header-1]){
  matrix.unmergeCells(`D${band}:AC${band}`);
  matrix.mergeCells(`D${band}:AG${band}`);
 }
 for(let req=26;req<=29;req++){
  const col=column(req+4);
  matrix.getRange(`${col}:${col}`).format.columnWidth=6.57;
  matrix.getRange(`${col}${header}`).copyFrom(matrix.getRange(`AC${header}`),'all');
  write(matrix,7,`${col}${header}`,`RF${req}`);
  for(let index=1;index<=16;index++){
   matrix.getRange(`${col}${header+index}`).copyFrom(matrix.getRange(`AC${header+index}`),'all');
   write(matrix,7,`${col}${header+index}`,null);
  }
 }
 for(let index=1;index<=16;index++){
  const row=header+index;
  for(let req=1;req<=25;req++){
   const col=column(req+4);
   write(matrix,7,`${col}${row}`,inputs.mapping[index-1].includes(req)?'X':null);
  }
 }
}
matrix.getRange('A7:M7').format.wrapText=true;
matrix.getRange('A7').format.rowHeight=30;
workbook.recalculate();
await fs.writeFile(path.join(work,'changes.json'),JSON.stringify(changes,null,2));
const file=await SpreadsheetFile.exportXlsx(workbook);
await file.save(path.join(work,'stage.xlsx'));
console.log((await workbook.inspect({kind:'match',searchTerm:'#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A|#NUM!|#NULL!|#SPILL!',options:{useRegex:true,maxResults:10}})).ndjson);
for(const [index,area,name] of [[0,'A28:Q35','rf'],[1,'B3:C18','rnf'],[6,'A7:AG16','matriz']]){
 const sheet=workbook.worksheets.getItemAt(index);
 const p=await workbook.render({sheetName:sheet.name,range:area,scale:0.8,format:'png'});
 await fs.writeFile(path.join(work,`${name}.png`),new Uint8Array(await p.arrayBuffer()));
}
console.log('Etapa completada.');
