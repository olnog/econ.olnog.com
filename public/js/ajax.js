$.get('/ajax', function(data){
  let obj = JSON.parse(data)
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
  itemCapacity  = obj.itemCapacity
  items         = obj.items
  itemTypes     = obj.itemTypes
  labor         = obj.labor
  currentskillPoints = labor.availableSkillPoints
  land          = obj.land
  leases        = obj.leases
  numOfItems    = obj.numOfItems
  formatSkillsObjectFromDB(obj.skills)
  robots        = obj.robots
  settings      = obj.settings
  statusHistory = obj.statusHistory
  userID        = obj.userID
  username      = obj.username
  console.log(actions)
  refreshUI()
})
