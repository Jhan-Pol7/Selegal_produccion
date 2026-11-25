import fs from 'node:fs/promises';
import path from 'node:path';
import { createRequire } from 'node:module';
import { pathToFileURL } from 'node:url';
const [dependencies,source,work]=process.argv.slice(2);
const require=createRequire(import.meta.url);
const {FileBlob,SpreadsheetFile}=await import(pathToFileURL(require.resolve('@oai/artifact-tool',{paths:[dependencies]})));
const data=JSON.parse(await fs.readFile(path.join(work,'data.json'),'utf8'));
const stories=JSON.parse(await fs.readFile(path.join(work,'hu.json'),'utf8'));
const workbook=await SpreadsheetFile.importXlsx(await FileBlob.load(source));
const [rf,rnf,stake,priority,,,matrix,hu]=Array.from({length:8},(_,i)=>workbook.worksheets.getItemAt(i));
const changes={'1':{},'2':{},'3':{},'4':{},'7':{},'8':{}};
const formats={'1':[],'2':[],'3':[],'4':[],'7':[],'8':[]};
const heights={'1':[],'2':[],'3':[],'4':[],'7':[],'8':[]};
const put=(sheet,id,address,value)=>{
 changes[id][address]=value;
 if(value===null) sheet.getRange(address).clear({applyTo:'contents'});
 else sheet.getRange(address).values=[[value]];
};
const fmt=(id,address)=>formats[id].push(address);
const rowHeight=(sheet,id,row,points)=>{sheet.getRange(`A${row}`).format.rowHeight=points;heights[id].push(row);};
const col=(value)=>{let s='';for(let n=value;n;n=Math.floor((n-1)/26))s=String.fromCharCode(65+(n-1)%26)+s;return s;};
const originalRf=[];
for(let r=6;r<=30;r++)originalRf.push({name:rf.getRange(`E${r}`).values[0][0],activity:rf.getRange(`B${r}`).values[0][0],process:rf.getRange(`A${r}`).values[0][0]});
for(let index=0;index<4;index++){
 const row=31+index,item=data.newRf[index];
 for(const c of 'BCDEFGHIJKLMNOQ')rf.getRange(`${c}${row}`).copyFrom(rf.getRange(`${c}30`),'all');
 if(row!==34)rf.getRange(`A${row}`).copyFrom(rf.getRange('A30'),'all');
 rf.getRange(`P${row}`).copyFrom(rf.getRange('P30'),'all');
 const values=[item[0],item[1],item[2],item[3],`El sistema debe ${item[4][0].toLowerCase()+item[4].slice(1)}.`,item[5]];
 for(let j=0;j<6;j++){put(rf,'1',`${'ABCDEF'[j]}${row}`,values[j]);fmt('1',`${'ABCDEF'[j]}${row}`);}
 for(const c of 'GHIJKLMNO'){put(rf,'1',`${c}${row}`,c==='H'?1:2);fmt('1',`${c}${row}`);}
 rf.getRange(`P${row}`).formulas=[[`=SUM(G${row}:O${row})`]];fmt('1',`P${row}`);
 put(rf,'1',`Q${row}`,[true,false,true,false][index]?'Sí':'No');fmt('1',`Q${row}`);
 rf.getRange(`B${row}:F${row}`).format.wrapText=true;rowHeight(rf,'1',row,40);
}

const additions=[
 [17,'RNF16','El sistema debe funcionar correctamente en navegadores modernos como Chrome, Edge, Firefox y Safari.\n'],
 [27,'RNF17','El sistema debe proteger los formularios con CSRF, almacenar contraseñas mediante hash y limitar los intentos de acceso y los envíos de solicitudes.'],
 [28,'RNF18','El sistema debe impedir más de una cita por solicitud y evitar que dos citas activas ocupen el mismo turno del centro.']
];
for(const [row,id,text] of additions){
 for(const c of 'BC')rnf.getRange(`${c}${row}`).copyFrom(rnf.getRange(`${c}26`),'all');
 put(rnf,'2',`B${row}`,id);
 put(rnf,'2',`C${row}`,text);
 for(const c of 'BC')fmt('2',`${c}${row}`);
 rnf.getRange(`C${row}`).format.wrapText=true;rowHeight(rnf,'2',row,28);
}

// En el archivo original solo hay una interesada identificada por nombre.
for(let row=4;row<=29;row++){
 const name=stake.getRange(`B${row}`).values[0][0];
 if(!name)continue;
 for(const c of 'DEFGH')put(stake,'3',`${c}${row}`,null);
 if(String(name).toLowerCase().includes('miluska')){put(stake,'3',`G${row}`,'A');put(stake,'3',`H${row}`,'D');}
 else put(stake,'3',`D${row}`,'A / D');
}

const categoryColor={'must':'#A9D18E','should':'#FFE699','could':'#C69C6D','won\'t':'#F46A6A','wont':'#F46A6A'};
for(let row=6;row<=31;row++){
 const value=priority.getRange(`D${row}`).values[0][0];
 if(!value)continue;
 const key=String(value).trim().toLowerCase();
 const match=Object.keys(categoryColor).find(k=>key.startsWith(k));
 if(match){priority.getRange(`D${row}`).format.fill=categoryColor[match];fmt('4',`D${row}`);}
}
const categories=['Must Have','Should Have','Must Have','Should Have'];
for(let i=0;i<4;i++){
 const row=32+i,pmv=52+i,item=data.newRf[i],story=stories[16+i],category=categories[i];
 for(const c of 'ABCDEF'){
  priority.getRange(`${c}${row}`).copyFrom(priority.getRange(`${c}30`),'all');
  priority.getRange(`${c}${pmv}`).copyFrom(priority.getRange(`${c}51`),'all');
 }
 const cells=[item[3],item[4],item[5],category,`Apoya ${story.para}.`,category==='Must Have'?'Alta':'Media'];
 for(let j=0;j<6;j++){put(priority,'4',`${'ABCDEF'[j]}${row}`,cells[j]);fmt('4',`${'ABCDEF'[j]}${row}`);}
 const extra=[item[3],item[4],item[5],category==='Must Have'?'Sí':'No',category,category==='Must Have'?'Versión 1 - PMV':'Versión posterior'];
 for(let j=0;j<6;j++){put(priority,'4',`${'ABCDEF'[j]}${pmv}`,extra[j]);fmt('4',`${'ABCDEF'[j]}${pmv}`);}
 priority.getRange(`D${row}`).format.fill=categoryColor[category.toLowerCase().startsWith('must')?'must':'should'];
 priority.getRange(`E${pmv}`).format.fill=categoryColor[category.toLowerCase().startsWith('must')?'must':'should'];
 priority.getRange(`B${row}:F${row}`).format.wrapText=true;priority.getRange(`B${pmv}:F${pmv}`).format.wrapText=true;
 rowHeight(priority,'4',row,105);rowHeight(priority,'4',pmv,105);
}
priority.getRange('C:C').format.columnWidth=46;

for(let i=0;i<stories.length;i++){
 const row=6+i,item=stories[i],first=item.rf[0],sourceRow=first<=25?originalRf[first-1]:{process:data.newRf[first-26][0],activity:data.newRf[first-26][1],name:data.newRf[first-26][4]};
 if(row>13)for(const c of 'ABCDEFGHIJ')hu.getRange(`${c}${row}`).copyFrom(hu.getRange(`${c}13`),'all');
 const names=item.rf.map(n=>n<=25?originalRf[n-1].name:data.newRf[n-26][4]);
 const cells=[sourceRow.process,sourceRow.activity,item.como,item.rf.map(n=>`RF${String(n).padStart(2,'0')}`).join(', '),names.join('\n'),item.criterios.join('\n'),item.id,item.como,item.quiero,item.para];
 for(let j=0;j<10;j++){put(hu,'8',`${'ABCDEFGHIJ'[j]}${row}`,cells[j]);fmt('8',`${'ABCDEFGHIJ'[j]}${row}`);}
 hu.getRange(`A${row}:K${row}`).format.wrapText=true;
 hu.getRange(`A${row}:K${row}`).format.verticalAlignment='top';
 for(const c of 'ABCDEFGHIJK')fmt('8',`${c}${row}`);
 rowHeight(hu,'8',row,180);
}

// Los códigos se conservan tal como figuran en la hoja original. Se añade la referencia de celda para distinguir duplicados.
const nrfRows=[5,6,9,10,12,13,16,17,21,22,25,26,30,31,32,33,27,28];
const nrfLabels=nrfRows.map(r=>`${rnf.getRange(`B${r}`).values[0][0]} (${r})`);
const nrfMap=[[1,2,4,5,6,7],[4,6],[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16],[4,5,6,14],[10,11,13],[7,10,11,13,17,18,19,20],[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16],[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16],[1],[4,5],[4,5,6,7,10,11,13,14,17,18,19,20],[4,5],[4,5],[4,5],[8,11],[7,10,11,13,17,18,19,20],[4,5,19],[14,17]];
put(matrix,'7','A7','X indica relación entre HU y requisito. En RNF, el número entre paréntesis indica la fila de la hoja de requisitos no funcionales.');
matrix.getRange('A7:M7').format.wrapText=true;fmt('7','A7');rowHeight(matrix,'7',7,36);
for(let n=30;n<=51;n++)matrix.getRange(`${col(n)}:${col(n)}`).format.columnWidth=n<=33?8:16;
const fillHeaders=(row)=>{
 for(let req=26;req<=29;req++){
  const address=`${col(req+4)}${row}`;
  matrix.getRange(address).copyFrom(matrix.getRange(`AC${row}`),'all');put(matrix,'7',address,`RF${req}`);fmt('7',address);
 }
 for(let j=0;j<nrfLabels.length;j++){
  const address=`${col(34+j)}${row}`;
  matrix.getRange(address).copyFrom(matrix.getRange(`AC${row}`),'all');put(matrix,'7',address,nrfLabels[j]);
  matrix.getRange(address).format.wrapText=true;fmt('7',address);
 }
 rowHeight(matrix,'7',row,36);
};
const fillData=(row,story,sourceRow)=>{
 for(let req=1;req<=47;req++){
  const address=`${col(req+4)}${row}`;
  if(req>25){matrix.getRange(address).copyFrom(matrix.getRange(`AC${sourceRow}`),'all');fmt('7',address);}
  const linked=req<=29?story.rf.includes(req):nrfMap[req-30].includes(Number(story.id.slice(-3)));
  put(matrix,'7',address,linked?'X':null);
 }
};
for(let phase=0;phase<6;phase++){
 const header=10+phase*20;
 for(const r of [header-2,header-1]){matrix.unmergeCells(`D${r}:AC${r}`);matrix.mergeCells(`D${r}:AG${r}`);matrix.mergeCells(`AH${r}:AY${r}`);}
 put(matrix,'7',`AH${header-2}`,'REQUERIMIENTOS NO FUNCIONALES');
 put(matrix,'7',`AH${header-1}`,['FASE DE ANÁLISIS','FASE DE DISEÑO','FASE DE PROGRAMACIÓN','FASE DE PRUEBAS','FASE DE DESPLIEGUE','FASE DE MANTENIMIENTO'][phase]);
 for(const r of [header-2,header-1]){
  matrix.getRange(`AH${r}:AY${r}`).format.fill=r===header-2?'#002060':'#FFC000';
  matrix.getRange(`AH${r}:AY${r}`).format.font={name:'Arial',size:10,bold:true,color:r===header-2?'#FFFFFF':'#000000'};
  matrix.getRange(`AH${r}:AY${r}`).format.horizontalAlignment='center';
  put(matrix,'7',`AH${r}`,r===header-2?'REQUERIMIENTOS NO FUNCIONALES':['FASE DE ANÁLISIS','FASE DE DISEÑO','FASE DE PROGRAMACIÓN','FASE DE PRUEBAS','FASE DE DESPLIEGUE','FASE DE MANTENIMIENTO'][phase]);
  fmt('7',`AH${r}`);
 }
 fillHeaders(header);
 for(let i=0;i<16;i++){
  const row=header+i+1,story=stories[i];
  put(matrix,'7',`C${row}`,`Como ${story.como}, quiero ${story.quiero} para ${story.para}.`);
  put(matrix,'7',`D${row}`,'Vínculo');
  matrix.getRange(`C${row}`).format.wrapText=true;fmt('7',`C${row}`);rowHeight(matrix,'7',row,42);
  fillData(row,story,row);
 }
 const section=129+phase*6;
 for(const c of ['A','B','C','D']){matrix.getRange(`${c}${section}`).copyFrom(matrix.getRange(`${c}10`),'all');fmt('7',`${c}${section}`);}
 put(matrix,'7',`C${section}`,['FASE DE ANÁLISIS','FASE DE DISEÑO','FASE DE PROGRAMACIÓN','FASE DE PRUEBAS','FASE DE DESPLIEGUE','FASE DE MANTENIMIENTO'][phase]+' · HU-017 a HU-020');
 const subhead=section+1;
 for(let c=1;c<=29;c++){const letter=col(c);matrix.getRange(`${letter}${subhead}`).copyFrom(matrix.getRange(`${letter}${header}`),'all');fmt('7',`${letter}${subhead}`);}
 for(const [letter,value] of [['A','N°'],['B','Código_HU'],['C','HISTORIA DE USUARIO'],['D','Uso']])put(matrix,'7',`${letter}${subhead}`,value);
 for(let req=1;req<=25;req++)put(matrix,'7',`${col(req+4)}${subhead}`,`RF${String(req).padStart(2,'0')}`);
 fillHeaders(subhead);
 for(let i=0;i<4;i++){
  const row=section+2+i,story=stories[16+i];
  for(const c of 'ABCD'){matrix.getRange(`${c}${row}`).copyFrom(matrix.getRange(`${c}${header+1+i}`),'all');fmt('7',`${c}${row}`);}
  put(matrix,'7',`A${row}`,17+i);put(matrix,'7',`B${row}`,story.id);put(matrix,'7',`C${row}`,`Como ${story.como}, quiero ${story.quiero} para ${story.para}.`);put(matrix,'7',`D${row}`,'Vínculo');
  matrix.getRange(`C${row}`).format.wrapText=true;rowHeight(matrix,'7',row,40);
  fillData(row,story,header+1+i);
 }
}
workbook.recalculate();
await fs.writeFile(path.join(work,'manifest.json'),JSON.stringify({changes,formats,heights,nrfRows},null,2));
const result=await SpreadsheetFile.exportXlsx(workbook);await result.save(path.join(work,'authored.xlsx'));
console.log((await workbook.inspect({kind:'match',searchTerm:'#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A|#NUM!|#NULL!|#SPILL!',options:{useRegex:true,maxResults:20}})).ndjson);
for(const [sheet,range,name] of [[rf,'A28:Q35','rf-final'],[rnf,'B20:C33','rnf-final'],[stake,'B3:H10','interesados-final'],[priority,'A28:F35','prioridad-final'],[matrix,'A7:AY16','matriz-final'],[matrix,'A129:AY134','continuacion-final'],[hu,'A5:K10','hu-final']]){
 const preview=await workbook.render({sheetName:sheet.name,range,scale:0.8,format:'png'});
 await fs.writeFile(path.join(work,`${name}.png`),new Uint8Array(await preview.arrayBuffer()));
}
console.log('Preparado catálogo, interesados, MoSCoW, criterios y matriz.');
