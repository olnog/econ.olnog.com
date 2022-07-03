class Robot{
  processAndSendRobotActions(){
    let robotData = []
    let statusArr = null
    for (let i in robots){
      $("#robotStatus" + i).html()
      $("#robotError" + i).html()
      if ($("#robotAction" + robots[i].id).val() != 'nothing'){
        robotData.push( {id: robots[i].id, defaultAction: $("#robotAction" + robots[i].id).val()})
      }
    }
    $.post( "/robotActions", {robotData: JSON.stringify(robotData), _token: fetchCSRF() }).done(function(data){
      displayHeaders(JSON.parse(data).info)
      csrfToken = JSON.parse(data).csrf
      statusArr = JSON.parse(data).statusArr
      $("#robotsElectricity").html(JSON.parse(data).electricity.toLocaleString())

      let robotStopped = true
      for (let i in statusArr){
        if ('error' in statusArr[i]){
          $("#robotError" + i).html(statusArr[i].error)
        } else {
          if (robotStopped){
            robotStopped = false
          }
          $("#robotStatus" + i).html(statusArr[i].status)
          $("#robotStatus" + i).addClass('fw-bold')
          setTimeout(function(){
            $("#robotStatus" + i).removeClass('fw-bold')
          }, 750)
        }
      }
      if (robotStopped){
        stopRobot()
      }
    })
  }

  program(actionName){
    if (actionName == 'null'){
      return
    }
    $.post( "/robots", {actionName: actionName.split(' ').join('-'), _token: fetchCSRF() }).done(function(data){
      if (JSON.parse(data).error != undefined){
        displayError(JSON.parse(data).error)
        return
      }
      robots = JSON.parse(data).robots
      status(JSON.parse(data).status)

    })
  }

  reprogram(actionName, id){
    if (actionName == 'null'){
      return
    }
    $.post( "/robots/" + id, {actionName: actionName, _token: fetchCSRF(), _method: 'PUT' }).done(function(data){
      if (JSON.parse(data).error != undefined){
        displayError(JSON.parse(data).error)
        return
      }
      robots = JSON.parse(data).robots
      status(JSON.parse(data).status)
      setTimeout(loadPage('actions'), 500)
    })
  }

  start(){
    $("#robotStart").addClass('d-none')
    $("#robotStop").removeClass('d-none')
    robot.processAndSendRobotActions()
    $("#robotAnimation").removeClass('d-none')
    robotAnimating = setInterval(robotAnimation, 100)
    robotAutomation = setInterval(function(){
      robot.processAndSendRobotActions()

    }, 4000)
  }

  stop(){
    $("#robotStart").removeClass('d-none')
    $("#robotStop").addClass('d-none')
    $("#robotAnimation").addClass('d-none')
    clearInterval(robotAutomation)
    robotAutomation = null
    clearInterval(robotAnimating)
    robotAnimating = null

  }
}
