<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>incremental economy</title>
  </head>
  <body>
    <h1 class='text-center'>
      <a href='/'> Try Full Game</a>
    </h1>

    <ul class="nav justify-content-center nav-tabs">
      <li class="nav-item">
        <a class="nav-link" href="#">land</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="#">labor</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">capital</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">state</a>
      </li>
    </ul>
    <div id='status' class='text-center'>&nbsp;</div>

    <div class='mb-3'>
      clacks:
      <span id='clacks'></span>
    </div>
    <div id='land' class='d-none otherPages ms-3'>
      There is no land here.
    </div><div id='labor' class='otherPages ms-3'>

      <div class='fw-bold'>
        Actions
      </div>
      <div id='actionsSection' class='ms-3'>
        <div>
          Work Hours:
          <span id='workHours'></span>
          <input type='checkbox' id='eatFood' checked> Eat Food?
        </div><div>
          Equipped: <span id='equipped'>nothing</span>
        </div>
        <div id='actionListing' class='ms-3'></div>
      </div>
      <div class='fw-bold mt-3'>
        Skills
        <button id='hide-skillsSection' class='hide btn btn-link'>
          -
        </button>
        <button id='show-skillsSection' class='show btn btn-link d-none'>
          +
        </button>
      </div>

      <div id='skillsSection' class='ms-3'>
        <div class="progress">
          <div id='skillPointProgress' class="progress-bar" role="progressbar"
            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div>
          Available Skill Points:
          <span id='availableSkillPoints'></span>
        </div>
        <div id='skillListing' class='ms-3'></div>
      </div>
    </div><div id='capital' class='d-none otherPages ms-3'>
      <div class='fw-bold'>
        buildings
      </div><div id='buildingListings' class='ms-3'>

      </div><div class='fw-bold'>
        items
      </div><div id='itemListings' class='ms-3'>

      </div>
    </div><div id='state'  class='d-none otherPages ms-3'>

    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src='https://code.jquery.com/jquery-3.6.0.js'></script>
    <script src='js/old/variables.js'></script>
    <script src='js/old/ui.js'></script>

    <script src='js/old/js.js'></script>
    <script src='js/old/action.js'></script>
    <script src='js/old/events.js'></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
  </body>
</html>
