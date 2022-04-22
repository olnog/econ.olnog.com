$("#skillPointProgress").attr('aria-valuemax', Math.round(startingWorkHours / remainingSkillPoints))
for (i in items){
  buyOrders[i] = {quantity: 10, cost: 10}
}
refreshUI()



function buildBuilding(typeOfBuilding){
  console.log(typeOfBuilding)
  for (material in buildingCosts[typeOfBuilding]){
    items[material] -= buildingCosts[typeOfBuilding][material]
  }
  buildings[typeOfBuilding] = { durabilityCaption: buildingDurabilityCaption[skills.construction.rank],
    uses: Math.pow(10, skills.construction.rank), totalUses: Math.pow(10, skills.construction.rank)}
  status ("You built a " + typeOfBuilding + ".")
}

function createTool(toolName, material){

  if (material == "iron"){
    skillName = 'toolmakingIron'
  } else if (material == "stone"){
    skillName = 'toolmakingStone'
  }
  if (items[qualityCaption[skills[skillName].rank] + " " + material + " " + toolName] == undefined){
    items[qualityCaption[skills[skillName].rank] + " " + material + " " + toolName] = 1
    buyOrders[qualityCaption[skills[skillName].rank] + " " + material + " " + toolName] = {quantity: 10, cost: 10 }
  } else {
    items[qualityCaption[skills[skillName].rank] + " " + material + " " + toolName]++
  }
}

function dedashify(arr){
  actionArr = arr.split('-')
  let txt = ""
  for (n in actionArr){
    txt += actionArr[n] + " "
  }
  return txt.substring(0, txt.length-1)
}

function destroyTool(){
  equipped = {
    type: null,
    caption: 'nothing',
    uses: 0,
    totalUses:0
  }
}

function doTheyHaveThisBuilding(typeOfBuilding){
  if (buildings[typeOfBuilding] != undefined && buildings[typeOfBuilding].uses > 0){
    return true
  }
  return false
}

function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
function isThisActionPossible(actionName){
  if (actionName == 'gather-stone'
    || actionName == 'gather-wood'
    || (actionName=='explore' && skills.exploring.rank > 0)
    || ((actionName=='make-stone-axe' || actionName == "make-stone-pickaxe"
    || actionName == "make-stone-saw")
      && skills.toolmakingStone.rank > 0)
    || (actionName == 'chop-tree' && skills.lumberjacking.rank > 0 )
    || (actionName == 'mine-stone' && skills.miningStone.rank > 0 )
    || (actionName == 'mill-log' && skills.sawmilling.rank > 0 )
    || (actionName == 'hunt' && skills.hunting.rank > 0)
    || (actionName == 'cook-meat' && skills.cooking.rank > 0)
    || (actionName == 'mine-coal' && skills.miningCoal.rank > 0)
    || (actionName == 'mine-iron-ore' && skills.miningIron.rank > 0)
    || (actionName == "smelt-iron-ore" && skills.smeltingIron.rank > 0)
    || ((actionName=='make-iron-axe' || actionName == "make-iron-pickaxe"
    || actionName == "make-iron-saw")
      && skills.toolmakingIron.rank > 0)



  ){
    return true
  }
  return false
}

function isThisActionAvailable(actionName){
  console.log(actionName)
  if (actionName == 'gather-stone'
    || actionName == 'gather-wood'
    || (actionName=='explore' && skills.exploring.rank > 0)
    || ((actionName=='make-stone-axe' || actionName == "make-stone-pickaxe"
    || actionName == "make-stone-saw")
      && skills.toolmakingStone.rank > 0 && items.wood > 0 && items.stone > 0)
    || (actionName == 'chop-tree' && skills.lumberjacking.rank > 0 && equipped.type == 'axe' && land.forest > 0)
    || (actionName == 'mine-stone' && skills.miningStone.rank > 0 && equipped.type == 'pickaxe' && land.mountains > 0)
    || (actionName == 'mill-log' && skills.sawmilling.rank > 0 && equipped.type == 'saw' && items.logs > 0)
    || (actionName == 'hunt' && skills.hunting.rank > 0)
    || (actionName == 'cook-meat' && skills.cooking.rank > 0 && items.meat > 0 && doTheyHaveThisBuilding('campfire'))
    || (actionName == 'mine-coal' && skills.miningCoal.rank > 0 && equipped.type == 'pickaxe' && land.mountains > 0)
    || (actionName == 'mine-iron-ore' && skills.miningIron.rank > 0 && equipped.type == 'pickaxe' && land.mountains > 0)
    || (actionName == 'smelt-iron-ore' && skills.smeltingIron.rank > 0 && items.coal >= 10 && items.ironOre >= 10
      && (doTheyHaveThisBuilding('largeFurnace') || doTheyHaveThisBuilding('smallFurnace')))
    || ((actionName=='make-iron-axe' || actionName == "make-iron-pickaxe"
    || actionName == "make-iron-saw")
      && skills.toolmakingIron.rank > 0 && items.wood > 0 && items.iron > 0)

  ){
    console.log(actionName)
    return true
  }
  return false
}

function status(text){
  $("#status").html(text)
}

function useBuilding(typeOfBuilding){
  buildings[typeOfBuilding].uses--
}
