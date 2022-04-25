function displayItems(){
  let html = ""
  for (i in items){
    let noQuantity = ""
    let bookButton = ""
    let buttonCaption = " "
    let contractButtonCaption = ""
    let toolCaption = ""
    let sellingContract = isThereASellContract(items[i].itemTypeID)
    let buyFromContract = ''
    if (sellingContract != null && sellingContract.cost != null && clacks >= sellingContract.cost ){
      buyFromContract = "<button id='buyFromSellOrder-" + sellingContract.id + "-1"
      + "' class='buyFromSellOrder btn btn-danger m-2'>buy (-" + sellingContract.cost.toLocaleString()
      + " clacks)</button>"
      if (clacks >= sellingContract.cost * 10){
        buyFromContract += "<button id='buyFromSellOrder-" + sellingContract.id + "-10"
        + "' class='buyFromSellOrder btn btn-danger m-2'>buy x10(-" + (sellingContract.cost * 10).toLocaleString()
        + " clacks)</button>"
      }
      if (clacks >= sellingContract.cost * 100){
        buyFromContract += "<button id='buyFromSellOrder-" + sellingContract.id + "-100"
        + "' class='buyFromSellOrder btn btn-danger m-2'>buy x100(-" + (sellingContract.cost * 100).toLocaleString()
        + " clacks)</button>"
      }
    }
    let buyingContract = isThereABuyContract(items[i].itemTypeID)
    let sellToContract = ''
    if (buyingContract != null && buyingContract.cost != null && items[i].quantity > 0 ){
      sellToContract = "<button id='sellToBuyOrder-" + buyingContract.id + "-1"
      + "' class='sellToBuyOrder btn btn-success m-2'>sell (+" + buyingContract.cost.toLocaleString()
      + " clacks)</button>"
      if (items[i].quantity >= 10){
        sellToContract += "<button id='sellToBuyOrder-" + buyingContract.id + "-10"
        + "' class='sellToBuyOrder btn btn-success m-2'>sell x10(+" + (buyingContract.cost * 10).toLocaleString()
        + " clacks)</button>"
      }
      if (items[i].quantity >= 100){
        sellToContract += "<button id='sellToBuyOrder-" + buyingContract.id + "-100"
        + "' class='sellToBuyOrder btn btn-success m-2'>sell x100(+" + (buyingContract.cost * 100).toLocaleString()
        + " clacks)</button>"
      }
    }

    if (items[i].quantity == 0){
      noQuantity = "noQuantity d-none"
    }
    if ((items[i].name == 'Pickaxe' || items[i].name == 'Axe'
    || items[i].name == 'Saw' || items[i].name == 'Handmill'
    || items[i].name == 'Shovel' || items[i].name == 'Radiation Suit'
    || items[i].name.substring(0, 'Chainsaw'.length) == 'Chainsaw'
    || items[i].name.substring(0, 'Jackhammer'.length) == 'Jackhammer'
    || (items[i].name.substring(0, 'Car'.length) == 'Car'
      && items[i].name != 'Carbon Nanotubes')
    || items[i].name.substring(0, 'Tractor'.length) == 'Tractor'
    || items[i].name.substring(0, 'Bulldozer'.length) == 'Bulldozer'

    )){
      if(items[i].quantity > 0){
        buttonCaption = "<button id='equipItem-" + items[i].id + "' class='equipItem btn btn-info m-3'> equip </button> "
      }
      if (items[i].material != null){
        toolCaption = " (" + items[i].material + " / " + items[i].durability + ") "
      }
    } else if (items[i].name == 'Food'){
      $("#laborFood").html(items[i].quantity.toLocaleString())
    } else if (items[i].name == 'Contracts'){
      contractButtonCaption = "If you want to post an order on the market, you need to have a Contract. ";
      $(".createContract").addClass('d-none')
      if ( items[i].quantity > 0){
        $(".createContract").removeClass('d-none')
        contractButtonCaption = "<a href='/contracts/create' id='createContract' class='btn btn-link' >[ post on the market ]</a>"
      }
      $("#newContactInContracts").html(contractButtonCaption)
    } else if (items[i].name == 'Robots' && items[i].quantity > 0
      && skills.robotics.rank > 0){
      buttonCaption = "<button id='programRobot'class='btn btn-link ms-3'>[ program & activative ]</button> <select id='robotSkillList'></select>"

    } else if (items[i].name == 'Electricity' && items[i].quantity > 0){

      $("#robotsElectricity").html(items[i].quantity.toLocaleString())
    } else if (items[i].name == 'HerbMeds' || items[i].name == 'BioMeds' || items[i].name == 'NanoMeds'){
      $("#labor" + items[i].name).html(items[i].quantity.toLocaleString())
    } else if (items[i].name == 'Books' && items[i].quantity >= labor.availableSkillPoints + labor.allocatedSkillPoints){
      bookButton = "<button id='readBook' class='ms-3 btn btn-warning'>Read "
        + Number(labor.availableSkillPoints + labor.allocatedSkillPoints) +
        + " Books</button>"
    }
    let sellCaption = ""
    if (isThereABuyOrderForThis(items[i].itemTypeID, items[i].quantity)){
      sellCaption = "<button id='sellToStateFromItems-"
        + fetchBuyOrderForItemType(items[i].itemTypeID)
        + "' class='sellToStateFromItems btn btn-link'>[ sell to state ]</button>"
    }
    dumpButton = ""
    if (items[i].quantity >= 1){
      dumpButton += "<button id='dump-" + items[i].id + "-1' class='btn btn-danger me-2 d-none dump'>dump 1x</button>"
    }
    if (items[i].quantity >= 10){
      dumpButton += "<button id='dump-" + items[i].id + "-10' class='btn btn-danger me-2 d-none dump'>10x</button>"
    }
    if (items[i].quantity >= 100){
      dumpButton += "<button id='dump-" + items[i].id + "-100' class='btn btn-danger me-2 d-none dump'>100x</button>"
    }
    if (items[i].name == 'Nuclear Waste'){
      dumpButton = ''
      sellCaption = ''
    }
    html += "<div class='mt-3 " + noQuantity + "'><div>"
      + dumpButton + items[i].name +  toolCaption
    +  ": " + items[i].quantity.toLocaleString()  + buttonCaption + sellCaption + bookButton
    + contractButtonCaption
    + "</div><div>"
    + sellToContract + buyFromContract

    + "</div></div>"
  }
  $("#itemListings").html(html)
  formatItems()
}
