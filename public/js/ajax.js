$.get('/ajax', function(data){
  let obj = JSON.parse(data)
/*
  actions       = obj.actions

  autoBribe     = obj.autoBribe
  avgBribe      = obj.avgBribe
  buildings     = obj.buildings
  buildingLeases= obj.buildingLeases
  buildingSlots = obj.buildingSlots
  buyOrders     = obj.buyOrders
  clacks        = obj.clacks
  contracts     = obj.contracts
  equipment     = obj.equipment
  hostileTakeover = obj.hostileTakeover
  items         = obj.items
  itemTypes     = obj.itemTypes
  labor         = obj.labor
  currentskillPoints = labor.availableSkillPoints
  land          = obj.land
  leases        = obj.leases
  numOfContracts= obj.numOfContracts
  numOfItems    = obj.numOfItems
  numOfParcels  = obj.numOfParcels
  */
  robots        = obj.robots
/*
  settings      = obj.settings
  userID        = obj.userID
  username      = obj.username

  refreshUI()
  */
})
