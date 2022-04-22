$(document).on('click', '.action', function(e) {
  action(e.target.id.substring('action-'.length))
  refreshUI()
})

$(document).on('click', '#build', function(e) {
  let typeOfBuilding = $("#possibleBuildings").val()
  buildBuilding(typeOfBuilding)
  refreshUI()
})

$(document).on('click', '.equip', function(e) {
  maxNumberOfUses = [null, 10, 30, 90, 270, 1000]
  quality = e.target.id.substring('equip-'.length).split('-')[0]
  equipped.type = e.target.id.substring('equip-'.length).split('-')[2]
  equipped.uses = maxNumberOfUses[qualityCaption.indexOf(quality)]
  equipped.totalUses = maxNumberOfUses[qualityCaption.indexOf(quality)]
  items[dedashify(e.target.id.substring('equip-'.length))]--
  equipped.caption = dedashify(e.target.id.substring('equip-'.length))
  refreshUI()
})

$(document).on('change', '#possibleBuildings', function(e) {
  if ($("#possibleBuildings").val() != ""){
    $("#build").removeAttr('disabled')
  }
})

$(document).on('click', '.hide', function(e) {
  $("#show-" + e.target.id.split('-')[1]).removeClass('d-none')
  $("#hide-" + e.target.id.split('-')[1]).addClass('d-none')
  $("#" + e.target.id.split('-')[1]).addClass('d-none')
})


$(document).on('click', '.incrementSkill', function(e) {
  if (availableSkillPoints > skills[e.target.id.split('-')[1]].rank && skills[e.target.id.split('-')[1]].rank != 5){
    availableSkillPoints -= skills[e.target.id.split('-')[1]].rank+1
    skills[e.target.id.split('-')[1]].rank ++
  }
  refreshUI()
})

$(document).on('click', '.nav-link', function(e) {
  $(".nav-link").removeClass('active')
  $(this).addClass('active')
  $(".otherPages").addClass('d-none')
  $("#" + e.target.innerHTML).removeClass('d-none')

})

$(document).on('click', '.sell', function(e) {
  let itemType = e.target.id.split('-')[1]
  clacks += buyOrders[itemType].cost
  items[itemType] -= buyOrders[itemType].quantity
  buyOrders[itemType].quantity *= 10
  buyOrders[itemType].cost *= 10
  refreshUI()
})

$(document).on('click', '.show', function(e) {
  $("#hide-" + e.target.id.split('-')[1]).removeClass('d-none')
  $("#show-" + e.target.id.split('-')[1]).addClass('d-none')
  $("#" + e.target.id.split('-')[1]).removeClass('d-none')
})
