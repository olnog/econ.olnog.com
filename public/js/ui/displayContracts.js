function displayContracts(){
  console.log('displayContracts')
  let html = ""
  let previousItems = []
  let alwaysHireableActions = ['harvest-wheat', 'harvest-herbal-greens',
    'harvest-plant-x', 'harvest-rubber', 'plant-herbal-greens-field',
    'plant-plant-x-field', 'plant-rubber-plantation', 'plant-wheat-field']
  for (let i in contracts){
    let category = contracts[i].category
    let contractUsername = contracts[i].username
    let signContract = ""
    let notMyContractClass = ''
    if (contracts[i].username == username ){
      contractUsername = 'You are'
    } else {
      notMyContractClass = ' notMyContract '
    }
    itemTypeClass = ''
    landClass = ''
    if (contracts[i].category == 'buyOrder'
      || contracts[i].category == 'sellOrder'){
      itemTypeClass = " itemClass" + contracts[i].itemTypeID + ' '
      if (!previousItems.includes(contracts[i].itemTypeID)){
        previousItems.push(contracts[i].itemTypeID)
      }
    } else if (contracts[i].category == 'buyLand'
      || contracts[i].category == 'sellLand'
      || contracts[i].category == 'lease'){
      landClass = " " + contracts[i].landType + " "
    }
    html += "<div class='mt-3 " + category + " " + landClass
      + notMyContractClass + itemTypeClass
      + " contracts p-3'><div >" + contractUsername
    if (contracts[i].category == 'lease'){
      if (clacks >= contracts[i].price){
        signContract = "<button id='lease-" + contracts[i].id
          + "' class='lease btn btn-danger'>accept lease</button>"
        if (haveTheyAlreadyLeased(contracts[i].id)){

          signContract = "<button id='cancelLease-" + contracts[i].id
            + "' class='cancelLease btn btn-warning ms-3'>cancel lease</button>"
            + " You are currently leasing this land."
        }

      }
      html += " <span class='fw-bold'>leasing</span> " + contracts[i].landType + " at "
        + contracts[i].price.toLocaleString() + " clack(s) per use"

    } else if (contracts[i].category == 'reproduction'){
      //if (labor.workHours >= 1000){
        signContract = "<button id='reproduction-" + contracts[i].id
          + "' class='reproduction btn btn-success'>reproduce</button>"
      //}
      html += " paying anyone " + contracts[i].price.toLocaleString() + " clack(s) to <span class='fw-bold'>create and raise a child</span> for them (you will <span class='fw-bold text-danger'>Rebirth</span> but will be paid your fee after - to avoid the estate tax)"
    } else if (contracts[i].category == 'sellOrder'){
      let itemName = fetchItemName(contracts[i].itemTypeID)
      if (clacks >= contracts[i].price){
        signContract = "<button id='buyFromSellContract-" + contracts[i].id
          + "-1' class='buyFromSellContract btn btn-danger'>buy x1</button>"
      }
      if (clacks >= contracts[i].price * 10 ){
        signContract += "<button id='buyFromSellContract-" + contracts[i].id
          + "-10' class='buyFromSellContract btn btn-danger'>buy x10</button>"
      }
      if (clacks >= contracts[i].price * 100  ){
        signContract += "<button id='buyFromSellContract-" + contracts[i].id
          + "-100' class='buyFromSellContract btn btn-danger'>buy x100</button>"
      }
      if (clacks >= contracts[i].price * 1000 ){
        signContract += "<button id='buyFromSellContract-" + contracts[i].id
          + "-1000' class='buyFromSellContract btn btn-danger'>buy x1000</button>"
      }
      html += " <span class='fw-bold'>selling</span> <span class='text-decoration-underline'>"
      + itemName + "</span> for " + contracts[i].price.toLocaleString() + " clack(s) each until "
      if (contracts[i].until == 'gone'){
        html += itemName + " runs out"
      } else if (contracts[i].until == 'sold'){
        html += " they've sold " + contracts[i].condition.toLocaleString() + " "
          + itemName + " [ sold " + contracts[i].conditionFulfilled.toLocaleString() +  " so far ]"
      } else if (contracts[i].until == 'earn'){
        html += " they earn a certain amount of money"
      }
    } else if (contracts[i].category == 'buyOrder'){
      let itemName = fetchItemName(contracts[i].itemTypeID)
      signContract = "<div class='ms-3'>You have "
        + howManyItems(contracts[i].itemTypeID).toLocaleString() + " " + itemName + "</div><div class='ms-3'>"
      if (doTheyHaveItemsQuant(contracts[i].itemTypeID, 1)){
        signContract += "<button id='sellToBuyContract-" + contracts[i].id
          + "-1' class='sellToBuyContract btn btn-success'>sell x1</button>"
      }
      if (doTheyHaveItemsQuant(contracts[i].itemTypeID, 10)){
        signContract += "<button id='sellToBuyContract-" + contracts[i].id
          + "-10' class='sellToBuyContract btn btn-success'>sell x10</button>"
      }
      if (doTheyHaveItemsQuant(contracts[i].itemTypeID, 100)){
        signContract += "<button id='sellToBuyContract-" + contracts[i].id
          + "-100' class='sellToBuyContract btn btn-success'>sell x100</button>"
      }
      if (doTheyHaveItemsQuant(contracts[i].itemTypeID, 1000)){
        signContract += "<button id='sellToBuyContract-" + contracts[i].id
          + "-1000' class='sellToBuyContract btn btn-success'>sell x1000</button>"
      }
      signContract += "</div>"
      html += " <span class='fw-bold'>buying</span> <span class='text-decoration-underline'>"
      + itemName + "</span> for " + contracts[i].price.toLocaleString() + " clack(s) each until "
      if (contracts[i].until == 'gone'){
        html += " they run out of money or space"
      } else if (contracts[i].until == 'bought'){
        html += contracts[i].condition.toLocaleString() + " " + itemName
          + " bought [ bought " + contracts[i].conditionFulfilled.toLocaleString() +  " so far ]"
      } else if (contracts[i].until == 'inventory'){
        html += " they have a certain amount in inventory"
      } else if (contracts[i].until == 'spend'){
        html += " they've spent at least a certain amount to buy [ they've spent "
          + contracts[i].conditionFulfilled.toLocaleString() + " so far ]"
      }

    } else if (contracts[i].category == 'hire'){
      if (alwaysHireableActions.includes(contracts[i].action) || actions.available.includes(contracts[i].action)){
        signContract = "<button id='hire-" + contracts[i].id
          + "' class='hire btn btn-success'>" + contracts[i].action + "</button>"
      }
      html += " <span class='fw-bold'>hiring</span> anyone to <span class='fw-bold'>"
        + dedashify(contracts[i].action) + "</span> for " + contracts[i].price.toLocaleString()
        + " clack(s) until "
      if (contracts[i].until == 'gone'){
        html += ' money runs out.'
      } else if (contracts[i].until == 'finite'){
        html += ' this action has been done ' + contracts[i].condition.toLocaleString()
          + " time(s) [ done " + contracts[i].conditionFulfilled.toLocaleString() + " time(s) ]"
      }
      html += " (required minimum skill level: " + contracts[i].minSkillLevel + ")"
    } else if (contracts[i].category == 'freelance'){
      if (clacks >= contracts[i].price){
        signContract = "<button id='freelance-" + contracts[i].id
          + "' class='freelance btn btn-danger'>" + contracts[i].action
          + "</button>"
      }
      html += " available to <span class='fw-bold'>freelance</span> " + contracts[i].action + " for " + contracts[i].price.toLocaleString() + " clack(s) until"
      if (contracts[i].until == 'workHours'){
        html += " there are no more work hours available."
      } else if (contracts[i].until == 'food'){
        html += " food runs out."
      } else if (contracts[i].until == 'finite'){
        html += " they've freelanced " + contracts[i].condition.toLocaleString() + " time(s) [ "
          + contracts[i].conditionFulfilled.toLocaleString() + " times so far ]"
      }
      if (contracts[i].minSkillLevel != null){
        html += " they have a skill level of " + contracts[i].minSkillLevel
      }
    } else if (contracts[i].category == 'construction'){
      signContract = ''
      if (clacks >= contracts[i].price){
        if (buildings.repairable.length > 0 ){
          signContract = "<button id='repairContract-" + contracts[i].id + "' class='repairContract btn btn-danger btn-lg'>repair</button><select id='repairContractBuilding-" + contracts[i].id + "'><option></option>"
          for (let i in buildings.repairable){
            signContract += "<option value='" + buildings.repairable[i] + "'>"
              + fetchBuilding(buildings.repairable[i]).name + "</option>"
          }
          signContract += "</select>"
        }
        /*
        if (actions.buildings.length > 0){
          signContract += "<button id='buildContract-" + contracts[i].id + "' class='ms-5 buildContract btn btn-danger btn-lg'>build</button><select id='buildContractBuilding-" + contracts[i].id + "'><option></option>"
          for (let i in actions.buildings){
            signContract += "<option>" + actions.buildings[i] + "</option>"
          }
          signContract += "</select>"
        }
        */
      }
      html += " willing to <span class='fw-bold'>build or repair</span> for "
        + contracts[i].price.toLocaleString()
        + " clack(s). They have a Construction skill of "
        + contracts[i].minSkillLevel

    } else if (contracts[i].category == 'repairDead' || contracts[i].category == 'repairLess'){
      signContract = "<button id='repairContract-" + contracts[i].id
        + "' class='repairContract btn btn-success'> repair</button>"
      html += " paying " + contracts[i].price.toLocaleString()
        + " clacks(s) for someone with a Construction skill (+"
        + contracts[i].minSkillLevel + ") to repair a building when its condition is "
      if (contracts[i].category == 'repairDead'){
         html += " at 0%"
      } else {
        html += " less than 100%"
      }
      html += " until "
      if (contracts[i].until == 'gone'){
        html += " their money runs out."
      } else if (contracts[i].until == 'finite'){
        html += " it's been repaired " + contracts[i].condition.toLocaleString()
          + " time(s) [ repaired " + contracts[i].conditionFulfilled.toLocaleString() + " time(s) so far ]"
      }
    } else if (contracts[i].category == 'buyLand'){
      if (doTheyOwnThisTypeOfLand(contracts[i].landType)){
        signContract = "<button id='buyLand-" + contracts[i].id
          + "' class='buyLand btn btn-success'>sell</button>"
      }
      html+= " <span class='fw-bold'>buying</span> " + contracts[i].landType
        + " for " + contracts[i].price.toLocaleString() + " clack(s) each until "
      if (contracts[i].until == 'gone'){
        html += " the money runs out."
      } else if (contracts[i].until == 'finite'){
        html += contracts[i].condition.toLocaleString() + " pieces of land bought [ bought  " + contracts[i].conditionFulfilled.toLocaleString()
          + " so far ]"
      }
    } else if (contracts[i].category == 'sellLand'){
      if (clacks >= contracts[i].price){
        signContract = "<button id='sellLand-" + contracts[i].id
          + "' class='sellLand btn btn-danger'>buy</button>"
      }
      html+= " <span class='fw-bold'>selling</span> <button id='goToLand-"
        + contracts[i].landID + "' class='goToLand btn btn-link'>parcel #"
        + contracts[i].landID + "</button> ("
        + fetchTypeOfLand(contracts[i].landID) + ") for "
        + contracts[i].price.toLocaleString() + " clack(s) "
    } else if (contracts[i].category == 'leaseBuilding'){
      if (clacks >= contracts[i].price){
        signContract = "<button id='leaseBuilding-" + contracts[i].id
          + "' class='leaseBuilding btn btn-danger'>lease "
          + contracts[i].buildingName + "</button>"
        if (haveTheyAlreadyLeasedABuilding(contracts[i].id)){
          signContract = "<button id='cancelBuildingLease-" + contracts[i].id
            + "' class='cancelBuildingLease btn btn-warning ms-3'>cancel building lease</button>"
            + " You are currently leasing a " + contracts[i].buildingName + "."
        }
      }
      html += " <span class='fw-bold'>leasing building</span> (<span class='text-decoration-underline'>"
        + contracts[i].buildingName + "</span>) for " + contracts[i].price + " clack(s)"

    }
    html += "</div><div>"
    if (contracts[i].userID == userID){
      html += "<button id='cancelContract-" + contracts[i].id + "' class='cancelContract btn btn-warning'>cancel</button>"
    } else {
      html += signContract
    }
    html += "</div></div>"
  }
  $("#contractListings").html(html)
  if ($(".nav-link.market.active").html() != 'items'
    || ($("#contractItemFilter").val() == "" || $("#contractItemFilter").html() == "")){
    generateItems(previousItems)
  }
  filterContractsByType()
  formatContracts()
}

function formatContracts(){

  $("#contractItemFilterDiv").addClass('d-none')
  $("#contractLandFilterDiv").addClass('d-none')
  $(".contracts").addClass('d-none')
  let marketTab = $(".nav-link.market.active").html()
  let marketArr = {
    'labor': ['freelance', 'hire', 'reproduction'],
    'land': ['buyLand', 'sellLand', 'lease'],
    'items': ['buyOrder', 'sellOrder'],
    'buildings': ['construction', 'leaseBuilding'],
  }
  if (marketTab == 'mine'){
    $(".contracts:not(.notMyContract)").removeClass('d-none')
  } else if (marketTab=='items'){
    $("#contractItemFilterDiv").removeClass('d-none')
  } else if (marketTab=='land'){
    $("#contractLandFilterDiv").removeClass('d-none')
  }
  for (let i in marketArr[marketTab]){
    $('.' + marketArr[marketTab][i]).removeClass('d-none')
  }
    /*
  if ($("#contractTypeFilter").val() == 'all'){
    $(".contracts").removeClass('d-none')
    if ($("#showOnlyMyContracts").is(':checked')){
      $(".notMyContract").addClass('d-none')
    }
    return
  }
  $(".contracts").addClass('d-none')

  if ($("#showOnlyMyContracts").is(':checked')){
    $('.' + $("#contractTypeFilter").val() + ":not(.notMyContract)" ).removeClass('d-none')

  } else {
    $('.' + $("#contractTypeFilter").val() ).removeClass('d-none')
  }
  */
  if ($("#contractItemFilter").val() != ''){
    $(".contracts").addClass('d-none')
    $(".itemClass" + $("#contractItemFilter").val()).removeClass('d-none')
  }
}

function filterContractsByType(){
  $('.contracts').addClass('d-none')
  $('.contractFilterByCategory:checked').each(function(e){
    $("." + this.value).removeClass('d-none')
  })
}
function generateItems(itemList){
  let allItems = {}
  let alphabeticalList = []
  let html = "<option></option>"
  for (let i in itemList){
    let itemName = fetchItemName(itemList[i])
    alphabeticalList.push(itemName)
    allItems [itemName] = itemList[i]
  }
  alphabeticalList.sort()
  for (let i in alphabeticalList){
    html += "<option value='" + allItems[alphabeticalList[i]] + "'>"
      + alphabeticalList[i] + "</option>"
  }
  $("#contractItemFilter").html(html)
}
