gdjs.LevelselectCode = {};
gdjs.LevelselectCode.localVariables = [];
gdjs.LevelselectCode.forEachIndex2 = 0;

gdjs.LevelselectCode.forEachObjects2 = [];

gdjs.LevelselectCode.forEachTemporary2 = null;

gdjs.LevelselectCode.forEachTotalCount2 = 0;

gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1= [];
gdjs.LevelselectCode.GDOrangeBubbleButtonObjects2= [];
gdjs.LevelselectCode.GDtimerObjects1= [];
gdjs.LevelselectCode.GDtimerObjects2= [];


gdjs.LevelselectCode.eventsList0 = function(runtimeScene) {

};gdjs.LevelselectCode.eventsList1 = function(runtimeScene) {

{


let isConditionTrue_0 = false;
isConditionTrue_0 = false;
isConditionTrue_0 = gdjs.evtTools.runtimeScene.sceneJustBegins(runtimeScene);
if (isConditionTrue_0) {
}

}


{

gdjs.copyArray(runtimeScene.getObjects("OrangeBubbleButton"), gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1);

for (gdjs.LevelselectCode.forEachIndex2 = 0;gdjs.LevelselectCode.forEachIndex2 < gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1.length;++gdjs.LevelselectCode.forEachIndex2) {
gdjs.LevelselectCode.GDOrangeBubbleButtonObjects2.length = 0;


gdjs.LevelselectCode.forEachTemporary2 = gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1[gdjs.LevelselectCode.forEachIndex2];
gdjs.LevelselectCode.GDOrangeBubbleButtonObjects2.push(gdjs.LevelselectCode.forEachTemporary2);
let isConditionTrue_0 = false;
if (true) {
{for(var i = 0, len = gdjs.LevelselectCode.GDOrangeBubbleButtonObjects2.length ;i < len;++i) {
    gdjs.LevelselectCode.GDOrangeBubbleButtonObjects2[i].SetLabelText(gdjs.LevelselectCode.GDOrangeBubbleButtonObjects2[i].getVariables().getFromIndex(0).getAsString(), (typeof eventsFunctionContext !== 'undefined' ? eventsFunctionContext : undefined));
}
}}
}

}


{



}


{

gdjs.copyArray(runtimeScene.getObjects("OrangeBubbleButton"), gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1);

let isConditionTrue_0 = false;
isConditionTrue_0 = false;
for (var i = 0, k = 0, l = gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1.length;i<l;++i) {
    if ( gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1[i].IsClicked((typeof eventsFunctionContext !== 'undefined' ? eventsFunctionContext : undefined)) ) {
        isConditionTrue_0 = true;
        gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1[k] = gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1[i];
        ++k;
    }
}
gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1.length = k;
if (isConditionTrue_0) {
/* Reuse gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1 */
{runtimeScene.getGame().getVariables().getFromIndex(0).setNumber(((gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1.length === 0 ) ? gdjs.VariablesContainer.badVariablesContainer : gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1[0].getVariables()).getFromIndex(0).getAsNumber());
}{gdjs.evtTools.runtimeScene.replaceScene(runtimeScene, "FullSokobanGame", false);
}}

}


};

gdjs.LevelselectCode.func = function(runtimeScene) {
runtimeScene.getOnceTriggers().startNewFrame();

gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1.length = 0;
gdjs.LevelselectCode.GDOrangeBubbleButtonObjects2.length = 0;
gdjs.LevelselectCode.GDtimerObjects1.length = 0;
gdjs.LevelselectCode.GDtimerObjects2.length = 0;

gdjs.LevelselectCode.eventsList1(runtimeScene);
gdjs.LevelselectCode.GDOrangeBubbleButtonObjects1.length = 0;
gdjs.LevelselectCode.GDOrangeBubbleButtonObjects2.length = 0;
gdjs.LevelselectCode.GDtimerObjects1.length = 0;
gdjs.LevelselectCode.GDtimerObjects2.length = 0;


return;

}

gdjs['LevelselectCode'] = gdjs.LevelselectCode;
