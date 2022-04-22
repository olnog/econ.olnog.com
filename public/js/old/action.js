function action (actionName){
  buildingCaption = ""
  if (actionName == 'gather-stone'){
    items.stone++
    status("You gathered 1 stone. You now have " + items.stone + ".")
  } else if (actionName == 'gather-wood'){
    items.wood += 10
    status("You gathered 10 wood. You now have " + items.wood + ".")
  } else if ((actionName=='make-stone-axe' || actionName == "make-stone-pickaxe"
  || actionName == "make-stone-saw")){
    items.stone--
    items.wood--
    createTool(actionName.split('-')[2], actionName.split('-')[1])
    status("You used 1 stone and 1 wood to make a " + actionName.split('-')[2] + ".")
  } else if (actionName == 'chop-tree'){
    items.logs ++
    equipped.uses--
    status("Using your axe, you chopped a tree down into one log. You now have " + items.logs + " logs.")
  } else if (actionName == 'mine-stone'){
    quantityMined = 10 * skills.miningCoal.rank
    if (doTheyHaveThisBuilding("mine")){
      useBuilding('mine')
      buildingCaption = " You used your mine."
      quantityMined *= 10
    }
    items.stone += quantityMined
    equipped.uses--
    status("Using your pickaxe, you mined " + quantityMined + " stone. You now have " + items.stone + " stone." + buildingCaption)
  } else if (actionName == 'mill-log'){
    items.logs--
    items.wood += 100
    equipped.uses--
    status("Using your saw, you sawed one log into 100 wood. You now have " + items.wood + " wood.")
  } else if (actionName == 'explore'){
    if (getRandomInt(1, foundLands + 1) == 1){
      let chanceToGenerateLand = getRandomInt(1, 10)
      if (chanceToGenerateLand < 6){
        typeOfLand = 'plains'
      } else if (chanceToGenerateLand > 8){
        typeOfLand  = 'mountains'
      } else {
        typeOfLand = 'forest'
      }
      land[typeOfLand]++
      foundLands++
      status("You explored and found a new piece of land no one's discovered before: " + typeOfLand + "!")
      return
    }
    status("You explored but didn't find anything.")
  } else if (actionName == 'hunt'){
    items.meat +=  skills.hunting.rank * 2
    status("You hunted " + skills.hunting.rank * 2 + " meat. You now have " + items.meat + ".")
  } else if (actionName == 'cook-meat'){
    useBuilding('campfire')
    items.meat--
    items.food += Number(skills.cooking.rank + 1)
    status("You cooked 1 meat into " + Number(skills.cooking.rank + 1) + " food. You now have " + items.food + ".")
  } else if (actionName == 'mine-coal'){
    quantityMined = 10 * skills.miningCoal.rank
    if (doTheyHaveThisBuilding("mine")){
      useBuilding('mine')
      buildingCaption = " You used your mine."
      quantityMined *= 10
    }
    items.coal += quantityMined
    equipped.uses--
    status("Using your pickaxe, you mined " + quantityMined + " coal. You now have " + items.coal + " coal."  + buildingCaption)
  } else if (actionName == 'mine-iron-ore'){
    let quantityMined = 10 * skills.miningIron.rank
    if (doTheyHaveThisBuilding("mine")){
      useBuilding('mine')
      buildingCaption = " You used your mine."
      quantityMined *= 10
    }
    items.ironOre += quantityMined
    equipped.uses--
    status("Using your pickaxe, you mined " + quantityMined + " iron ore. You now have " + items.ironOre + " iron ore." + buildingCaption)
  } else if (actionName == 'smelt-iron-ore'){
    let quantitySmelted = 1 * skills.smeltingIron.rank
    let ironOreRemoved = 10
    let coalRemoved = 10
    if (doTheyHaveThisBuilding("smallFurnace")){
      useBuilding('smallFurnace')
      buildingCaption = " You used your small furnace."
    } else if (doTheyHaveThisBuilding("largeFurnace") && items.ironOre >= 100 && items.coal >= 100){
      useBuilding('largeFurnace')
      buildingCaption = " You used your large furnace."
      quantitySmelted *= 10
      ironOreRemoved *= 10
      coalRemoved *= 10
    }
    items.ironOre -= ironOreRemoved
    items.coal -= coalRemoved
    items.iron += quantitySmelted
    status("You used " + ironOreRemoved + " iron ore and " + coalRemoved + " coal to smelt " + quantitySmelted + " iron. You now have " + items.iron + " iron." + buildingCaption)
  } else if ((actionName=='make-iron-axe' || actionName == "make-iron-pickaxe"
  || actionName == "make-iron-saw")){
    items.iron--
    items.wood--
    createTool(actionName.split('-')[2], actionName.split('-')[1])
    status("You used 1 iron and 1 wood to make a " + actionName.split('-')[2] + ".")
  }



  if (equipped.uses < 1){
    destroyTool()
  }
  if (items.food > 0 && $("#eatFood").is(":checked")){
    items.food--
    workHours--
    workHoursLearning ++
  } else {
    workHours -= 2
    workHoursLearning += 2

  }

  if (workHoursLearning >= Math.round(startingWorkHours / remainingSkillPoints)
    && allocatedSkillPoints < remainingSkillPoints){
    workHoursLearning = 0
    status("You learned something new and got one skill point.")
    availableSkillPoints++
    allocatedSkillPoints++
  }
  refreshUI()
}
