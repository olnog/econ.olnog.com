$.get('/ajax', function(data){
  displayHeaders(JSON.parse(data).info)
  robots        = JSON.parse(data).robots
})
