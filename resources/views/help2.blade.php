<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
</head><body>
<div class='text-center'>
  <a href='/home'>Home</a>
</div>
<div id='helpTOC'></div>
<h1 id='concepts' class='text-center'>
  Concepts
</h1>
<div id'workHours' class='fw-bold'>
  Work Hours
</div>
<div>
  Each time you do an action, you will use up one work hour. If you don't have any food, you will use up 2. Once you run out of Work Hours, you will have a Rebirth.
</div>
<div>
  Each time you rebirth, work hours goes up by 10%. This is good because it takes longer before your skills reset, but it's bad because your available skill points will take longer to increase.
</div>
<div id='available' class='fw-bold'>
  Available Skill Points
</div>
<div>
  You will get 1 new skill point every time you do a certain number of actions.
  That number is determined by dividing your starting work hours by your max
  skill points. So when you first start, you get 1000 work hours and 20 max
  skill points, this means you should get a skill point every 50 actions. (To gett more starting available skill points, get the Child Prodigy Rebirth perk.)
</div>
<div id='rebirth' class='fw-bold'>
  Rebirth
</div>
<div>
  When you do your rebirth, you keep all of your stuff (land, contracts, items, etc)
  but your skills will be reset back to 0. There is also a 10% tax on your Clacks, which can be mitigated through the Finance skill. (Each rank in Finance reduces this tax by 20%)
  Fortunately, you do get a few prestige perk options that are available. You can either:
  <div class='ms-3'>
    <span class='fw-bold'>Genius</span> - increase your maximum skill point capacity by 1 (must have a number of books equal to the number of max skill points)
  </div>
<div class='ms-3'>
    <span class='fw-bold'>Legacy</span> - start over at the same level in a specified skill (must have 1 child)
  </div>
<div class='ms-3'>
    <span class='fw-bold'>Child Prodigy</span> - start with one more available skill point at the expense of 10 max skill points (requires at least 25 max skill points)
  </div>

</div>
<div id='hostileTakeover' class='fw-bold'>
  Hostile Takeover
</div>
<div>
A player may attempt a hostile takeover on any land. Once a player tries to seize the property by putting in a bid equal to twice the current valuation, the owner may then put in their counter bid and a bidding war starts. The highest bid wins. Players have 24 hours to respond to each bid and once no one responds to a bid with a higher bid, that person wins.  Outside of the first bid, the minimum for bidding is equal to 1.10% of the previous bid. (this is arbitrary and can be changed later depending on how it works out)
</div>
<div>
The challenger will always lose their bids and the owner of the property will always get a new valuation in addition to the bids.
</div>
<div class='fw-bold ms-3'>
If the owner wins
</div>
<div class='ms-5'>
    • Owner loses their first counterbid; gains new valuation and all additional bids they made after their first counterbid
</div>
<div class='ms-5'>
    • Challenger loses all bids
</div>
<div  class='fw-bold ms-3'>
If owner loses
</div>
<div class='ms-5'>
    • Owner loses property; keeps their first counterbid
</div>
<div class='ms-5'>
    • Challenger loses all bids, but gains property at new valuation
</div>


<div id='itemAndBuildQuality' class='fw-bold'>
  Item & Build Quality
</div>
<div>
  When buildings are built, Construction determines what your build quality is. From 1 to 5, it is:
  'horribly built', 'poorly built', 'average built', 'well-built',
  'excellently built'. The 'horribly built' buildings can be used only 10 times, with each higher quality bulding ten times more effective than the last one.
</div>
<div>
  When tools are built, the toolmaking skill determines what the item quality is.
  From 1 to 5, it is: 'horrible', 'poor', 'average', 'good', 'great'.
  In this situation, item quality acts as a multiplier and the material creates
  a ten fold increase in uses with Stone having a base use of 10, iron 100, and steel 1000.
  The quality rank (1-5) is then multiplied by that base use.
</div>
<div id='landBonus' class='fw-bold'>
  Land Bonus
</div><div>
  Players get a bonus for the more land they have in chopping trees, mining and planting & harvesting rubber plantations. The bonus works as in you get +1 for one land, +2 for 3 total types (or 2 additional),  +3 for 6 total land types (or 3 additional), etc.
</div>



<h1  id='faq' class='text-center'>
FAQ
</h1>
<div id='faq-1' class='fw-bold'>
  I just repaired and it's repairing it worse than it did the last time. Why?
</div>
<div>
  The lower your construction, the worse your repairs will be. And each repair
  lowers the overall durability. The corresponding skill level with its max
  repair is (50%, 60%, 70%, 80%, 90%) So the first time you repair something
  at the lowest skill level, it'll be at 50% then %25, 12.5 and so on. Eventually, you won't be able to repair and will have to rebuild.
  <div id='faq-2' class='fw-bold'>
    How do I get money?
  </div>
<div>
    Check contracts to see what other players are buying - whether that's
    labor or items. Also, nearly every item can be sold to The State under
    the state tab if you have the appropriate quantity. Once you've established
    yourself, set up your own contracts to sell stuff of your own.
</div>
<div id='tabs-1' class='fw-bold'>
  What do the tabs do?
</div>
<div>
  history - shows everything you've previously done.
</div>
<div>
  land - all land currently in the game (your land is in bold)
</div>
<div>
  labor - all actions you are able to do and the corresponding skills. (Reset your skills here)
</div>
<div>
  capital - all buildings, items and equipment (once an item is equipped, it becomes equipment and cannot be sold.)
</div>
<div>
  contracts - create or fulfill contracts to buy land, sell land, buy items, sell items, repair buildings, build buildings, hire labor, or sell labor with other players
</div>
<div>
  chat - chat with other players in-game
</div>

<h1 id='items' class='text-center'>
  Items
</h1>
@foreach ($itemTypes as $itemType)
  <div class='fw-bold mt-3'>
    <span id='item{{$itemType->id}}'>
      {{$itemType->name}}
      @if ($itemType->material != null)
      ({{$itemType->material}} / {{$itemType->durability}})
      @endif
    </span>
    <a href='#helpTOC'>[ top ]</a>
  </div>
<div>
    {{$itemType->description}}
  </div>

@endforeach

<h1  class='text-center'>
  Any more questions?
</h1>
<div>
  If you have any more questions, feel free to post them in chat. Or hit me up on
  <a href='https://www.reddit.com/user/olnog/'>reddit</a> or <a href='https://twitter.com/therealolnog'>twitter</a>
</div>
<h1 id='actions'>Actions</h1>
  <div id="action-chop-tree" class="actionHeading">
  chop-tree</div>
  <div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> lumberjacking</div>
  <div class="actionDescription mb-3">Must own or lease a Forest, be equipped with an Axe, and there is a land bonus for Forests. (See Land Bonus)</div>
  <div id="action-cook-meat" class="actionHeading">cook-meat</div>
  <div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> cooking</div>
  <div class="actionDescription mb-3">Must have a Campfire, Kitchen or Food Factor built. This requires 1 Wood & 1 Meat (Campfire), 10 Meat & 5 Wood (Kitchen), or 100 Meat & 100 Electricity (Food Factory)</div>
  <div id="action-cook-flour" class="actionHeading">cook-flour</div>
  <div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> cooking</div>
  <div class="actionDescription mb-3">Must have a Campfire, Kitchen or Food Factor built. This requires 1 Wood & 1 Flour (Campfire), 10 Flour & 5 Wood (Kitchen), or 100 Flour & 100 Electricity (Food Factory)</div>
  <div id="action-convert-corpse-to-genetic-material" class="actionHeading">convert-corpse-to-genetic-material</div>
  <div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
  <div class="actionDescription mb-3">Requires Clone Vat building, 1000 Electricity and 1 Corpse. Base Yield: 100 Genetic Material</div>
  <div id="action-convert-corpse-to-Bio-Material" class="actionHeading">convert-corpse-to-Bio-Material</div>
  <div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
  <div class="actionDescription mb-3">Requires Bio Lab, 1 Corpse and 100 Electricity. Base Yield: 100 Bio-Material</div>
  <div id="action-convert-herbal-greens-to-Bio-Material" class="actionHeading">convert-herbal-greens-to-Bio-Material</div>
  <div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires Bio Lab, 100 Herbal Greens and 100 Electricity. Base Yield: 100 Bio-Material</div>
<div id="action-convert-plant-x-to-Bio-Material" class="actionHeading">convert-plant-x-to-Bio-Material</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires Bio Lab, 100 Plant X and 100 Electricity. Base Yield: 100 Bio-Material</div>
<div id="action-convert-meat-to-Bio-Material" class="actionHeading">convert-meat-to-Bio-Material</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires Bio Lab, 100 Meat and 100 Electricity. Base Yield: 100 Bio-Material</div>
<div id="action-convert-wheat-to-Bio-Material" class="actionHeading">convert-wheat-to-Bio-Material</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires Bio Lab, 100 Wheat and 100 Electricity. Base Yield: 100 Bio-Material</div>
<div id="action-convert-coal-to-carbon-nanotubes" class="actionHeading">convert-coal-to-carbon-nanotubes</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Required by Chem Lab, 1000 Coal, & 100 Electricity. Base Yield: 10 Carbon Nanotubes</div>
<div id="action-convert-sand-to-silicon" class="actionHeading">convert-sand-to-silicon</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Required by Chem Lab, 1000 Sand, & 100 Electricity. Base Yield: 10 Silicon</div>
<div id="action-convert-wood-to-carbon-nanotubes" class="actionHeading">convert-wood-to-carbon-nanotubes</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Required by Chem Lab, 1000 Wood, & 100 Electricity. Base Yield: 10 Carbon Nanotubes</div>
<div id="action-convert-uranium-ore-to-plutonium" class="actionHeading">convert-uranium-ore-to-plutonium</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires Centrifuge, 1,000 Uranium Ore & 1,000 Electricity. Base Yield: 10 Plutonium</div>
<div id="action-explore" class="actionHeading">explore</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> exploring</div>
<div class="actionDescription mb-3">No Requirements. Satellites 100x the minimum chance to find new parcels. Satellites use 100 Electricity. Rank determines how many parcels are found when exploration is successful.</div>
<div id="action-gather-stone" class="actionHeading">gather-stone</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> </div>
<div class="actionDescription mb-3">No requirements</div>
<div id="action-gather-wood" class="actionHeading">gather-wood</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> </div>
<div class="actionDescription mb-3">No requirements</div>



<div id="action-generate-electricity-with-coal" class="actionHeading">generate-electricity-with-coal</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires a Coal Power Plant & 1000 Coal. Base Yield: 1000 Electricity</div>
<div id="action-generate-electricity-with-plutonium" class="actionHeading">generate-electricity-with-plutonium</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires 2 Skills (Nuclear Engineering & Electrical Engineering), Nuclear Power Plant, & 1,000 Plutonium. Base Yield: 1,000,000 Electricity. Leaves behind 1,000 Nuclear Waste </div>
<div id="action-harvest-herbal-greens" class="actionHeading">harvest-herbal-greens</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> farming</div>
<div class="actionDescription mb-3">Requires Herbal Greens Field that has been planted for 24 hours</div>
<div id="action-harvest-plant-x" class="actionHeading">harvest-plant-x</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> farming</div>
<div class="actionDescription mb-3">Requires Plant X Field that has been planted for 24 hours</div>
<div id="action-harvest-rubber" class="actionHeading">harvest-rubber</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> farming</div>
<div class="actionDescription mb-3">Requires Rubber Plantation that has been planted for 24 hours</div>
<div id="action-harvest-wheat" class="actionHeading">harvest-wheat</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> farming</div>
<div class="actionDescription mb-3">Requires Wheat Field that has been planted for 24 hours</div>
<div id="action-hunt" class="actionHeading">hunt</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> hunting</div>
<div class="actionDescription mb-3">No Requirements</div>
<div id="action-make-BioMeds" class="actionHeading">make-BioMeds</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires 2 Skills (Biological Engineering & Medicine), Bio Lab, 10 Electricity, 10 HerbMeds, & 10 BioMaterial. Base Yield: 1 BioMeds</div>
<div id="action-make-book" class="actionHeading">make-book</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> education</div>
<div class="actionDescription mb-3">Requires 100 Paper & 1 Available Skill Point</div>
<div id="action-make-contract" class="actionHeading">make-contract</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> contracting</div>
<div class="actionDescription mb-3">Requires 1 Paper</div>
<div id="action-make-CPU" class="actionHeading">make-CPU</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires CPU Fabrication Plant, 100 Silicon, 100 Copper & 1000 Electricity. Base Yield: 1 CPU </div>
<div id="action-make-diesel-engine" class="actionHeading">make-diesel-engine</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> machining</div>
<div class="actionDescription mb-3">Requires Machinist Shop, 40 Steel, 40 Iron, & 20 Copper. Base Yield: 1 Engine</div>
<div id="action-make-electric-motor" class="actionHeading">make-electric-motor</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> machining</div>
<div class="actionDescription mb-3">Requires Machinist Shop, 10 Steel, 10 Iron, & 5 Copper. Base Yield: 1 Motor</div>
<div id="action-make-gasoline-engine" class="actionHeading">make-gasoline-engine</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> machining</div>
<div class="actionDescription mb-3">Requires Machinist Shop, 40 Steel, 40 Iron, & 20 Copper. Base Yield: 1 Engine</div>
<div id="action-make-gas-motor" class="actionHeading">make-gas-motor</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> machining</div>
<div class="actionDescription mb-3">Requires Machinist Shop, 10 Steel, 10 Iron, & 5 Copper. Base Yield: 1 Motor</div>
<div id="action-make-HerbMed" class="actionHeading">make-HerbMed</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> medicine</div>
<div class="actionDescription mb-3">Requires 10 Herbal Greens. Base Yield: 1</div>
<div id="action-make-iron-axe" class="actionHeading">make-iron-axe</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Iron & 1 Wood. Base Yield: 1 Iron Tool</div>
<div id="action-make-iron-handmill" class="actionHeading">make-iron-handmill</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Iron & 1 Wood. Base Yield: 1 Iron Tool</div>
<div id="action-make-iron-pickaxe" class="actionHeading">make-iron-pickaxe</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Iron & 1 Wood. Base Yield: 1 Iron Tool</div>
<div id="action-make-iron-saw" class="actionHeading">make-iron-saw</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Iron & 1 Wood. Base Yield: 1 Iron Tool</div>
<div id="action-make-iron-shovel" class="actionHeading">make-iron-shovel</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Iron & 1 Wood. Base Yield: 1 Iron Tool</div>
<div id="action-make-NanoMeds" class="actionHeading">make-NanoMeds</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> medicine</div>
<div class="actionDescription mb-3">Requires 2 Skills (Nanotechnology & Medicine), Nano Lab, 10 Nanites, 10 BioMeds, & 100 Electricity. Base Yield: 1 NanoMed </div>
<div id="action-make-nanites" class="actionHeading">make-nanites</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> nanotechnology</div>
<div class="actionDescription mb-3">Requires Nano Lab, 100 Silicon, 100 Carbon Nanotubes, & 1,000 Electricity. Base Yield: 1 Nanite</div>
<div id="action-make-paper" class="actionHeading">make-paper</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> papermaking</div>
<div class="actionDescription mb-3">Requires 1 Wood. Base Yield: 10 Paper</div>
<div id="action-make-rocket-engine" class="actionHeading">make-rocket-engine</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires Propulsion Lab, 1,000 Electricity, 1,000 Jet Fuel, 1,000 Iron, & 1,000 Steel. Base Yield: 1 Rocket Engine</div>
<div id="action-make-solar-panel" class="actionHeading">make-solar-panel</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires Solar Panel Fabrication Plant, 100 Steel, 100 Copper, 100 Silicon, & 100 Electricity. Base Yield: 1 Solar Panel</div>
<div id="action-make-steel-axe" class="actionHeading">make-steel-axe</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Steel & 1 Wood. Base Yield: 1 Steel Tool</div>
<div id="action-make-steel-handmill" class="actionHeading">make-steel-handmill</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Steel & 1 Wood. Base Yield: 1 Steel Tool</div>
<div id="action-make-steel-pickaxe" class="actionHeading">make-steel-pickaxe</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Steel & 1 Wood. Base Yield: 1 Steel Tool</div>
<div id="action-make-steel-saw" class="actionHeading">make-steel-saw</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Steel & 1 Wood. Base Yield: 1 Steel Tool</div>
<div id="action-make-steel-shovel" class="actionHeading">make-steel-shovel</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Steel & 1 Wood. Base Yield: 1 Steel Tool</div>
<div id="action-make-stone-axe" class="actionHeading">make-stone-axe</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Stone & 1 Wood. Base Yield: 1 Stone Tool</div>
<div id="action-make-stone-handmill" class="actionHeading">make-stone-handmill</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Stone & 1 Wood. Base Yield: 1 Stone Tool</div>
<div id="action-make-stone-pickaxe" class="actionHeading">make-stone-pickaxe</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Stone & 1 Wood. Base Yield: 1 Stone Tool</div>
<div id="action-make-stone-saw" class="actionHeading">make-stone-saw</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Stone & 1 Wood. Base Yield: 1 Stone Tool</div>
<div id="action-make-stone-shovel" class="actionHeading">make-stone-shovel</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> toolmaking</div>
<div class="actionDescription mb-3">Requires 1 Stone & 1 Wood. Base Yield: 1 Stone Tool</div>
<div id="action-make-tire" class="actionHeading">make-tire</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> chemicalEngineering</div>
<div class="actionDescription mb-3">Requires Chem Lab, 10 Rubber & 10 Electricity</div>
<div id="action-make-radiation-suit" class="actionHeading">make-radiation-suit</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires Chem Lab, 100 Rubber & 100 Electricity</div>
<div id="action-make-robot" class="actionHeading">make-robot</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> robotics</div>
<div class="actionDescription mb-3">Requires Robotics Lab, 100,000 Electricity, 100 Steel, 100 copper, 10 CPU & 100 Electric Motors Base Yield: 1 Robot</div>
<div id="action-make-satellite" class="actionHeading">make-satellite</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires Propulsion Lab, 1 CPU, 100 Electricity, 100 Copper, 100 Steel, 5 Solar Panels, & 1 Rocket Engine Base Yield: 1 Satellite</div>
<div id="action-mill-flour" class="actionHeading">mill-flour</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> flourMilling</div>
<div class="actionDescription mb-3">Requires either 10 Flour and a Hand Mill equipped or 100 Flour and Gristmill</div>
<div id="action-mill-log" class="actionHeading">mill-log</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> sawmilling</div>
<div class="actionDescription mb-3">Requires 1 Log & a Saw equipped or 10 Logs & a Sawmill</div>
<div id="action-mine-coal" class="actionHeading">mine-coal</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> mining</div>
<div class="actionDescription mb-3">Requires owning or leasing a Mountain and a Pickaxe equipped. A mine 10x's production. Base Yield: 10 Coal (Quantity of Mountains creates a land bonus)</div>
<div id="action-mine-copper-ore" class="actionHeading">mine-copper-ore</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> mining</div>
<div class="actionDescription mb-3">Requires owning or leasing a Mountain and a Pickaxe equipped. A mine 10x's production. Base Yield: 10 Ore (Quantity of Mountains creates a land bonus)</div>
<div id="action-mine-iron-ore" class="actionHeading">mine-iron-ore</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> mining</div>
<div class="actionDescription mb-3">Requires owning or leasing a Mountain and a Pickaxe equipped. A mine 10x's production. Base Yield: 10 Ore (Quantity of Mountains creates a land bonus)</div>
<div id="action-mine-sand" class="actionHeading">mine-sand</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> mining</div>
<div class="actionDescription mb-3">Requires owning or leasing a Desert and a Shovel equipped. A mine 10x's production. Base Yield: 10 Sand (Quantity of Deserts creates a land bonus)</div>
<div id="action-mine-stone" class="actionHeading">mine-stone</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> mining</div>
<div class="actionDescription mb-3">Requires owning or leasing a Mountain and a Pickaxe equipped. A mine 10x's production. Base Yield: 10 Stone (Quantity of Mountains creates a land bonus)</div>
<div id="action-mine-uranium-ore" class="actionHeading">mine-uranium-ore</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> mining</div>
<div class="actionDescription mb-3">Requires owning or leasing a Mountain and a Pickaxe equipped. A mine 10x's production. Base Yield: 10 Ore (Quantity of Mountains creates a land bonus)</div>
<div id="action-plant-herbal-greens-field" class="actionHeading">plant-herbal-greens-field</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> farming</div>
<div class="actionDescription mb-3">Requires 1 Building Slot Base Yield: 10</div>
<div id="action-plant-plant-x-field" class="actionHeading">plant-plant-x-field</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> farming</div>
<div class="actionDescription mb-3">Requires 1 Building Slot Base Yield: 10</div>
<div id="action-plant-rubber-plantation" class="actionHeading">plant-rubber-plantation</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> farming</div>
<div class="actionDescription mb-3">Requires owning or leasing Jungle and 1 Building Slot Base Yield: 10</div>
<div id="action-plant-wheat-field" class="actionHeading">plant-wheat-field</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> farming</div>
<div class="actionDescription mb-3">Requires 1 Building Slot Base Yield: 10</div>
<div id="action-pump-oil" class="actionHeading">pump-oil</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires Oil Well & 10 Electricity Base Yield: 10 Oil</div>
<div id="action-refine-oil" class="actionHeading">refine-oil</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires Oil Refinery, 100 Oil, & 100 Electricity</div>
<div id="action-smelt-copper" class="actionHeading">smelt-copper</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> smelting</div>
<div class="actionDescription mb-3">Requires 10 Coal & 10 Copper Ore (Small Furnace), 100 Coal & 100 Copper Ore (Large Furnace), or 1000 Electricity & 1000 Copper Ore. Base Yield: 1, 10 or 100 Copper Ingots (respectively)</div>
<div id="action-smelt-iron" class="actionHeading">smelt-iron</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> smelting</div>
<div class="actionDescription mb-3">Requires 10 Coal & 10 Iron Ore (Small Furnace), 100 Coal & 100 Iron Ore (Large Furnace), or 1000 Electricity & 1000 Iron Ore. Base Yield: 1, 10 or 100 Iron Ingots (respectively)</div>
<div id="action-smelt-steel" class="actionHeading">smelt-steel</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> smelting</div>
<div class="actionDescription mb-3">Requires 10 Coal & 10 Iron Ingots (Small Furnace), 100 Coal & 100 Iron Ingots (Large Furnace), or 1000 Electricity & 1000 Iron Ingots. Base Yield: 1, 10 or 100 Steel Ingots (respectively)</div>
<div id="action-transfer-electricity-from-solar-power-plant" class="actionHeading">transfer-electricity-from-solar-power-plant</div>
<div class="actionSkill"><span class="fw-bold">Action Unlocked By Skill:</span> engineering</div>
<div class="actionDescription mb-3">Requires a Solar Power Plant that has aged enough to collect Electricity (at least an hour) Maxes out at 2400 ( I think)</div>


<script src='https://code.jquery.com/jquery-3.6.0.js'></script>

<script>
  let myIDs = document.querySelectorAll('*[id]')
  let html = ''
  for (let i in myIDs){
    let headingArr = ['Concepts', 'FAQ', 'Items', 'Any more questions?', 'Actions']
    let headingsClass = 'ms-5'
    if ($('#' + myIDs[i].id).html() == undefined){
      continue
    }
    if (headingArr.includes($('#' + myIDs[i].id).html().trim())){
      headingsClass = "fw-bold ms-3"
    }
    html += "<div class='" + headingsClass + " '><a href='#" + myIDs[i].id + "'>" + $('#' + myIDs[i].id).html() + "</a></div> "

  }
  $("#helpTOC").html(html)

</script>
</body></html>
