gdjs.LoseScenesCode = {};
gdjs.LoseScenesCode.localVariables = [];
gdjs.LoseScenesCode.GDReturnButtonLoseObjects1= [];
gdjs.LoseScenesCode.GDReturnButtonLoseObjects2= [];
gdjs.LoseScenesCode.GDLoseTextObjects1= [];
gdjs.LoseScenesCode.GDLoseTextObjects2= [];
gdjs.LoseScenesCode.GDFloorBackgroundObjects1= [];
gdjs.LoseScenesCode.GDFloorBackgroundObjects2= [];
gdjs.LoseScenesCode.GDtimerObjects1= [];
gdjs.LoseScenesCode.GDtimerObjects2= [];


gdjs.LoseScenesCode.eventsList0 = function(runtimeScene) {

{

gdjs.copyArray(runtimeScene.getObjects("ReturnButtonLose"), gdjs.LoseScenesCode.GDReturnButtonLoseObjects1);

let isConditionTrue_0 = false;
isConditionTrue_0 = false;
for (var i = 0, k = 0, l = gdjs.LoseScenesCode.GDReturnButtonLoseObjects1.length;i<l;++i) {
    if ( gdjs.LoseScenesCode.GDReturnButtonLoseObjects1[i].getBehavior("ButtonFSM").IsClicked((typeof eventsFunctionContext !== 'undefined' ? eventsFunctionContext : undefined)) ) {
        isConditionTrue_0 = true;
        gdjs.LoseScenesCode.GDReturnButtonLoseObjects1[k] = gdjs.LoseScenesCode.GDReturnButtonLoseObjects1[i];
        ++k;
    }
}
gdjs.LoseScenesCode.GDReturnButtonLoseObjects1.length = k;
if (isConditionTrue_0) {
{gdjs.evtTools.runtimeScene.replaceScene(runtimeScene, "FullSokobanGame", false);
}}

}


};

gdjs.LoseScenesCode.func = function(runtimeScene) {
runtimeScene.getOnceTriggers().startNewFrame();

gdjs.LoseScenesCode.GDReturnButtonLoseObjects1.length = 0;
gdjs.LoseScenesCode.GDReturnButtonLoseObjects2.length = 0;
gdjs.LoseScenesCode.GDLoseTextObjects1.length = 0;
gdjs.LoseScenesCode.GDLoseTextObjects2.length = 0;
gdjs.LoseScenesCode.GDFloorBackgroundObjects1.length = 0;
gdjs.LoseScenesCode.GDFloorBackgroundObjects2.length = 0;
gdjs.LoseScenesCode.GDtimerObjects1.length = 0;
gdjs.LoseScenesCode.GDtimerObjects2.length = 0;

gdjs.LoseScenesCode.eventsList0(runtimeScene);
gdjs.LoseScenesCode.GDReturnButtonLoseObjects1.length = 0;
gdjs.LoseScenesCode.GDReturnButtonLoseObjects2.length = 0;
gdjs.LoseScenesCode.GDLoseTextObjects1.length = 0;
gdjs.LoseScenesCode.GDLoseTextObjects2.length = 0;
gdjs.LoseScenesCode.GDFloorBackgroundObjects1.length = 0;
gdjs.LoseScenesCode.GDFloorBackgroundObjects2.length = 0;
gdjs.LoseScenesCode.GDtimerObjects1.length = 0;
gdjs.LoseScenesCode.GDtimerObjects2.length = 0;


return;

}

gdjs['LoseScenesCode'] = gdjs.LoseScenesCode;
