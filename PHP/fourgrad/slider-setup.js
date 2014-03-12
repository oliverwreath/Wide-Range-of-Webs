/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
var slider=new Array();
slider[1]=new Object();
slider[1].min=0;
slider[1].max=10;
slider[1].val=5;
slider[1].onchange=setBoxValue;
slider[2]=new Object();
slider[2].min=0;
slider[2].max=10;
slider[2].val=5;
slider[2].onchange=setBoxValue;
slider[3]=new Object();
slider[3].min=0;
slider[3].max=10;
slider[3].val=5;
slider[3].onchange=setBoxValue;
slider[4]=new Object();
slider[4].min=0;
slider[4].max=10;
slider[4].val=5;
slider[4].onchange=setBoxValue;
slider[5]=new Object();
slider[5].min=0;
slider[5].max=10;
slider[5].val=5;
slider[5].onchange=setBoxValue;
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function setBoxValue(val, box) {
    var b=document.getElementById('output'+box);
	val=Math.round(val);
	b.value=val;
}
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

