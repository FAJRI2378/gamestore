gdjs.WinScenesCode = {};
gdjs.WinScenesCode.localVariables = [];
gdjs.WinScenesCode.GDTextWinObjects1= [];
gdjs.WinScenesCode.GDTextWinObjects2= [];
gdjs.WinScenesCode.GDFloorBackgroundObjects1= [];
gdjs.WinScenesCode.GDFloorBackgroundObjects2= [];
gdjs.WinScenesCode.GDReturnButtonWinObjects1= [];
gdjs.WinScenesCode.GDReturnButtonWinObjects2= [];
gdjs.WinScenesCode.GDtimerObjects1= [];
gdjs.WinScenesCode.GDtimerObjects2= [];


gdjs.WinScenesCode.eventsList0 = function(runtimeScene) {

{

gdjs.copyArray(runtimeScene.getObjects("ReturnButtonWin"), gdjs.WinScenesCode.GDReturnButtonWinObjects1);

let isConditionTrue_0 = false;
isConditionTrue_0 = false;
for (var i = 0, k = 0, l = gdjs.WinScenesCode.GDReturnButtonWinObjects1.length;i<l;++i) {
    if ( gdjs.WinScenesCode.GDReturnButtonWinObjects1[i].getBehavior("ButtonFSM").IsClicked((typeof eventsFunctionContext !== 'undefined' ? eventsFunctionContext : undefined)) ) {
        isConditionTrue_0 = true;
        gdjs.WinScenesCode.GDReturnButtonWinObjects1[k] = gdjs.WinScenesCode.GDReturnButtonWinObjects1[i];
        ++k;
    }
}
gdjs.WinScenesCode.GDReturnButtonWinObjects1.length = k;
if (isConditionTrue_0) {
{gdjs.evtTools.runtimeScene.replaceScene(runtimeScene, "FullSokobanGame", false);
}}

}


};

gdjs.WinScenesCode.func = function(runtimeScene) {
runtimeScene.getOnceTriggers().startNewFrame();

gdjs.WinScenesCode.GDTextWinObjects1.length = 0;
gdjs.WinScenesCode.GDTextWinObjects2.length = 0;
gdjs.WinScenesCode.GDFloorBackgroundObjects1.length = 0;
gdjs.WinScenesCode.GDFloorBackgroundObjects2.length = 0;
gdjs.WinScenesCode.GDReturnButtonWinObjects1.length = 0;
gdjs.WinScenesCode.GDReturnButtonWinObjects2.length = 0;
gdjs.WinScenesCode.GDtimerObjects1.length = 0;
gdjs.WinScenesCode.GDtimerObjects2.length = 0;

gdjs.WinScenesCode.eventsList0(runtimeScene);
gdjs.WinScenesCode.GDTextWinObjects1.length = 0;
gdjs.WinScenesCode.GDTextWinObjects2.length = 0;
gdjs.WinScenesCode.GDFloorBackgroundObjects1.length = 0;
gdjs.WinScenesCode.GDFloorBackgroundObjects2.length = 0;
gdjs.WinScenesCode.GDReturnButtonWinObjects1.length = 0;
gdjs.WinScenesCode.GDReturnButtonWinObjects2.length = 0;
gdjs.WinScenesCode.GDtimerObjects1.length = 0;
gdjs.WinScenesCode.GDtimerObjects2.length = 0;


return;

}

gdjs['WinScenesCode'] = gdjs.WinScenesCode;
