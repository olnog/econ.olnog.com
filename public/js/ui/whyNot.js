$(document).on("click", ".whyNot", function(e){
  let actionName = e.target.id.substring('whyNot-'.length)
  if (e.target.id == 'build'){
    actionName = 'build'
  }
  whyNot(actionName)
})
/*
$(document).on("mouseenter", ".whyNot", function(e){
  whyNot(e.target.id.substring('whyNot-'.length))
})

$(document).on("mouseleave", ".whyNot", function(e){
  $('#error').html('&nbsp;')
})
*/


function whyNot(actionName){
  let whyNot = ''
  if (actionName == 'build'){
    if (!doTheyOwnLand()){
      whyNot = "You can't build because you don't have any land to build on. Explore, buy / lease land or do a hostile takeover."
    } else if (buildingSlots < 1){
      whyNot = "You can't build because you don't have any free building slots right now. "
    } else if (actions.buildings.length > 0){
      whyNot = "In order to build, you have to select which building you want first. (See what you can and can't build <a href='/buildingCosts'>here</a>) "
    } else {
      whyNot = "See what you can and can't build <a href='/buildingCosts'>here</a> "
    }
  } else if (actionName == 'chop-tree' ){
    if (!doTheyOwnLand()){
      whyNot = "You can't chop any trees because you do not own any land. Explore, buy / lease land or do a hostile takeover."
    } else if (!doTheyOwnThisTypeOfLand('forest')){
      whyNot = "You can't chop any trees because you don't own any forest."
    } else if (labor.equipped == null || fetchEquipped().name != 'Axe'){
      whyNot = "You can't chop any trees because you don't have an Axe equipped."
    }
  } else if (actionName == 'cook-meat' || actionName == 'cook-flour' ){
    if (!doTheyHaveThisBuilding('Kitchen') && !doTheyHaveThisBuilding('Campfire')){
      whyNot = "You do not have either a kitchen or campfire. Please build one first."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Meat'), 1) && actionName == 'cook-meat'){
      whyNot = "You can't cook because you do not have meat."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Flour'), 1) && actionName == 'cook-flour'){
      whyNot = "You can't cook because you do not have wheat."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Wood'), 1)){
      whyNot = "You can't cook because you don't have any wood to burn."
    } else {
      whyNot = "You probably need to repair your Kitchen or Campfire. "
    }
  } else if (actionName == 'convert-sand-to-silicon'){
    if (!doTheyHaveThisBuilding('Chem Lab')){
      whyNot = " You do not have a Chem Lab built. Please build one first."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Sand'), 1000)){
      whyNot = "You do not have enough Sand. You need 1000."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Electricity'), 100)){
      whyNot = "You do not have enough Electricity. You need 100."
    }

  } else if (actionName == 'convert-coal-to-carbon-nanotubes' || actionName == 'convert-wood-to-carbon-nanotubes'){
    if (!doTheyHaveThisBuilding('Chem Lab')){
      whyNot = " You do not have a Chem Lab built. Please build one first."
    } else if (actionName == 'convert-coal-to-carbon-nanotubes' && !doTheyHaveItemsQuant(fetchItemTypeIDByName('Coal'), 1000)){
      whyNot = "You do not have enough Coal. You need 1000."
    } else if (actionName == 'convert-wood-to-carbon-nanotubes' && !doTheyHaveItemsQuant(fetchItemTypeIDByName('Wood'), 1000)){
      whyNot = "You do not have enough Wood. You need 1000."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Electricity'), 100)){
      whyNot = "You do not have enough Electricity. You need 100."
    }


  } else if (actionName == 'generate-electricity-with-coal' ){
    if (!doTheyHaveThisBuilding('Coal Power Plant')){
      whyNot = "You don't have a Coal Power Plant."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Coal'), 1000)){
      whyNot = "You need 1000 Coal to generate electricity."
    }

  } else if (actionName == 'harvest-herbal-greens'){
    if (!doTheyHaveThisBuilding('Herbal Greens Field')){
      whyNot = "You haven't planted any Herbal Greens Fields yet."
    } else {
      whyNot = "The Herbal Greens Fields are not ready to be harvested yet. Check capital tab for more info."
    }

  } else if (actionName == 'harvest-plant-x'){
    if (!doTheyHaveThisBuilding('Plant X Field')){
      whyNot = "You haven't planted any Plant X Fields yet."
    } else {
      whyNot = "The Plant X Fields are not ready to be harvested yet. Check capital tab for more info."
    }
  } else if (actionName == 'harvest-rubber'){
    if (!doTheyHaveThisBuilding('Rubber Plantation')){
      whyNot = "You haven't planted any Rubber Plantations yet."
    } else {
      whyNot = "The Rubber Plantations are not ready to be harvested yet. Check capital tab for more info."
    }
  } else if (actionName == 'harvest-wheat' ){
    if (!doTheyHaveThisBuilding('Wheat Field')){
      whyNot = "You haven't planted any wheat fields yet."
    } else {
      whyNot = "The wheat fields are not ready to be harvested yet. Check capital tab for more info."
    }
  } else if (actionName == 'make-book' ){
    if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Paper'), 100)){
      whyNot = "You don't have enough paper (100) to make a Book."
    } else if (labor.availableSkillPoints < 1){
      whyNot = "You need an available skill point to make a Book."
    }
  } else if (actionName == 'make-contract' ){
    whyNot = "You don't have any paper to make a contract with."
  } else if (actionName == 'make-diesel-engine' || actionName == 'make-gasoline-engine'){
    if (!doTheyHaveThisBuilding('Machine Shop')){
      whyNot = "You need to build a Machine Shop first.";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Iron Ingots'), 40)){
      whyNot = "You do not have enough Iron Ingots. (40 needed)";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Steel Ingots'), 40)){
      whyNot = "You do not have enough Steel Ingots. (40 needed)";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Copper Ingots'), 20)){
      whyNot = "You do not have enough Copper Ingots. (20 needed)";
    }
  } else if (actionName == 'make-CPU'){
    if (!doTheyHaveThisBuilding('CPU Fabrication Plant')){
      whyNot = "You need to build a CPU Fabrication Plant first.";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Electricity'), 1000)){
      whyNot = "You do not have enough Electricity. (1000 needed)";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Silicon'), 100)){
      whyNot = "You do not have enough Silicon. (100 needed)";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Copper Ingots'), 100)){
      whyNot = "You do not have enough Copper Ingots. (100 needed)";
    }

  } else if (actionName == 'make-electric-motor' || actionName == 'make-gas-motor'){
    if (!doTheyHaveThisBuilding('Machine Shop')){
      whyNot = "You need to build a Machine Shop first.";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Iron Ingots'), 10)){
      whyNot = "You do not have enough Iron Ingots. (10 needed)";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Steel Ingots'), 10)){
      whyNot = "You do not have enough Steel Ingots. (10 needed)";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Copper Ingots'), 5)){
      whyNot = "You do not have enough Copper Ingots. (5 needed)";
    }
  } else if (actionName == 'make-nanites'){
    if (!doTheyHaveThisBuilding('Nano Lab')){
      whyNot = "You need to build a Nano Lab first.";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Electricity'), 1000)){
      whyNot = "You do not have enough Electricity. (1000 needed)";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Silicon'), 100)){
      whyNot = "You do not have enough Silicon. (100 needed)";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Carbon Nanotubes'), 100)){
      whyNot = "You do not have enough Carbon Nanotubes. (100 needed)";
    }

  } else if (actionName == 'make-paper' ){
    whyNot = "You don't have any Wood to make Paper with."

  } else if (actionName == 'make-iron-axe'
    || actionName == 'make-iron-pickaxe' || actionName == 'make-iron-saw'){
    if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Wood'), 1)){
      whyNot = "You can't make any iron tools because you don't have any Wood."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Iron Ingots'), 1)){
      whyNot = "You can't make any iron tools because you don't have any Iron Ingots."
    }
  } else if (actionName == 'make-solar-panel'){
    if (!doTheyHaveThisBuilding('Solar Panel Fabrication Plant')){
      whyNot = "You need to build a Solar Panel Fabrication Plant first.";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Electricity'), 100)){
      whyNot = "You do not have enough Electricity. (100 needed)";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Silicon'), 1000)){
      whyNot = "You do not have enough Silicon. (1000 needed)";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Copper Ingots'), 100)){
      whyNot = "You do not have enough Copper Ingots. (100 needed)";
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Steel Ingots'), 100)){
      whyNot = "You do not have enough Steel Ingots. (100 needed)";
    }

  } else if (actionName == 'make-steel-axe'
    || actionName == 'make-steel-pickaxe' || actionName == 'make-steel-saw'){
    if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Wood'), 1)){
      whyNot = "You can't make any steel tools because you don't have any Wood."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Steel Ingots'), 1)){
      whyNot = "You can't make any steel tools because you don't have any Steel Ingots."
    }
  } else if (actionName == 'make-stone-axe'
    || actionName == 'make-stone-pickaxe' || actionName == 'make-stone-saw'){
    if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Wood'), 1)){
      whyNot = "You can't make any stone tools because you don't have any Wood."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Stone'), 1)){
      whyNot = "You can't make any stone tools because you don't have any Stone."
    }

  } else if (actionName == 'make-tire' || actionName == 'make-radiation-suit'){
    if (!doTheyHaveThisBuilding('Chem Lab')){
      whyNot = " You do not have a Chem Lab built. Please build one first."
    }
    let requiredNum = 10
    if (actionName == 'make-radiation-suit'){
      requiredNum = 100
    }
    if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Rubber'), requiredNum)){
      whyNot = "You do not have enough Rubber. You need " + requiredNum + "."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Electricity'), requiredNum)){
      whyNot = "You do not have enough Electricity. You need " + requiredNum + "."
    }

  } else if (actionName == 'mill-flour'){
    if (labor.equipped == null || fetchEquipped().name != 'Handmill'){
      whyNot = "You can't mill flour because you do not have a Handmill equipped."
    } else     if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Wheat'), 1)){
      whyNot = "You can't mill flour because you do not have any Wheat."
    } else if (!doTheyHaveThisBuilding('Gristmill')){
      whyNot = "You can't mill flour because you have not built a Gristmill."
    }
  } else if (actionName == 'mill-log'){
    if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Logs'), 1)){
      whyNot = "You can't mill logs because you don't have any Logs."
    } else if (labor.equipped == null || fetchEquipped().name != 'Saw'){
      whyNot = "You can't mill logs because you do not have a Saw equipped."
    }

  } else if (actionName == 'mine-sand'){
    if (!doTheyOwnThisTypeOfLand('desert')){
      whyNot = "You can't mine any sand because you don't have access to desert."
    } else if (labor.equipped == null || fetchEquipped().name != 'Shovel'){
      whyNot = "You can't mine because you do not have a Shovel equipped."
    }
  } else if (actionName == 'mine-stone'
    || actionName == 'mine-coal' || actionName == 'mine-iron-ore'){
      if (!doTheyOwnThisTypeOfLand('mountains')){
        whyNot = "You can't mine anything because you don't have access to mountains."
      } else if (labor.equipped == null || fetchEquipped().name != 'Pickaxe'){
        whyNot = "You can't mine because you do not have a Pickaxe equipped."
      }
    } else if (actionName == 'plant-rubber-plantation' ){
      if (!doTheyOwnLand()){
        whyNot = "You can't plant now because don't have any land to plant on. Explore, buy / lease land or do a hostile takeover."
      } else if (buildingSlots < 1){
        whyNot = "You can't plant a Rubber Plantation because you don't have any free building slots right now. "
      } else if (!doTheyOwnThisTypeOfLand('jungle')){
        whyNot = "You can't plant a Rubber Plantation because you don't have any jungle. "
      }
  } else if (actionName == 'plant-wheat-field'
    ||  actionName == 'plant-plant-x-field'
    ||  actionName == 'plant-herbal-greens-field'){
    if (!doTheyOwnLand()){
      whyNot = "You can't plant now because don't have any land to plant on. Explore, buy / lease land or do a hostile takeover."
    } else if (buildingSlots < 1){
      whyNot = "You can't plant a field because you don't have any free building slots right now. "
    }
  } else if (actionName == 'pump-oil' ){
    if (!doTheyHaveThisBuilding('Oil Well')){
      whyNot = "You don't have an oil well. Go to do that first."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Electricity'), 10)){
      whyNot = "You need 10 electricity before you can pump oil."
    }
  } else if (actionName == 'refine-oil' ){
    if (!doTheyHaveThisBuilding('Oil Refinery')){
      whyNot = "You don't have an oil refinery. Go to do that first."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Electricity'), 100)){
      whyNot = "You need 100 Electricity before you can refine oil."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Oil'), 100)){
      whyNot = "You need 100 Oil before you can refine oil."
    } else {
      whyNot = "You need to have both Chemical Engineering & Petroleum Engineering to refine oil."
    }
  } else if (actionName == 'smelt-iron' || actionName == 'smelt-steel' || actionName == 'smelt-copper'){
    if (!doTheyHaveThisBuilding('Small Furnace') && !doTheyHaveThisBuilding('Large Furnace')){
      whyNot = "You can't smelt because yo don't have a Small Furnace or Large Furnace. Please build one first."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Coal'), 10)){
      whyNot = "You can't smelt because you don't have 10 Coal."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Copper Ore'), 10) && actionName == 'smelt-copper'){
      whyNot = "You can't smelt because you do not have 10 Copper Ore."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Iron Ore'), 10) && actionName == 'smelt-iron'){
      whyNot = "You can't smelt because you do not have 10 Iron Ore."
    } else if (!doTheyHaveItemsQuant(fetchItemTypeIDByName('Iron Ingots'), 10) && actionName == 'smelt-steel'){
      whyNot = "You can't smelt because you do not have 10 Iron Ingots."
    } else {
      whyNot = "Your Small Furnace or Large Furnace needs to be repaired. (Possibly)"
    }

  }
  $("#error").html(whyNot)
}
