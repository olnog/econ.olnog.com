@extends('layouts.app')

@section('content')
<div class='text-center'>
  <a href='/home'>Home</a>
</div>

<div class='fw-bold mt-3'>
  06/05/22
</div>
<div class='ms-3 mb-1'>
  So I stopped updating this a while back. I mainly update the changes through
  the Git Hub, but I will be updating major changes in the 
  <a href='https://discord.com/invite/CjETTDYKdU'>Discord</a>. If you guys
  -really- want me to put changes on here, I will. Just let me know.
</div>
<div class='fw-bold mt-3'>
  04/20/22
</div><div class='ms-3 mb-1'>
  Forgot to actually implemented land deductions for chop tree, mine sand and pump oil.
</div><div class='ms-3 mb-1'>
  Removed chat. There's no one here but I would really prefer Discord because I can get notifications.
</div><div class='ms-3 mb-1'>
  Split capital tab into buildings and items tabs
</div><div class='ms-3 mb-1'>
  1/3 of all players don't even assign skill points so I've streamlined it and created a mandatory screen to encourage players to assign skill points
</div><div class='fw-bold mt-3'>
  04/20/22
</div><div class='ms-3 mb-1'>
  Added a way to filter items by buying or selling in markets tab
</div><div class='ms-3 mb-1'>
  Fixed a bug where land was unprotected despite auto-bribing
</div><div class='ms-3 mb-1'>
  Max skill points have been reimplemented and there is now a mechanism for players to increase their max skill points. Basically, once you've maxed it out, if you keep doing actions you won't get anymore available skill points but each time you would normally get an available skill point, you'll get a new skill point on rebirth(up to 15).
</div><div class='ms-3 mb-1'>
  Moved discord, help, and changelist to the bottom of the page instead of the top.
</div><div class='ms-3 mb-1'>
  Reimplemented children. Reproduction contracts require a rebirth. (fee is paid after rebirth to avoid estate tax being applied)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Fixed a bug where all skills weren't being reset at Rebirth.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Fixed a bug where players could hypothetically equip Carbon Nanotubes (CAR)
</div><div class='fw-bold mt-3'>
  04/18/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>HUGE CHANGE</span>
  Alright, so I gave this a lot of thought, and in the interest of making rebirth more meaningful. I've done the following:
  <div class='ms-3'>
    &#8226; Work hours have been removed. Players now can arbitrarily choose when they wish to Rebirth.
  </div><div class='ms-3'>
    &#8226; Skill points are no longer capped, players continue to gain available skill points indefinitely but the rate of increase  slows down with each new available skill point.
  </div><div class='ms-3'>
    &#8226; Food is how players are able to automate tasks.
  </div><div class='ms-3'>
    &#8226; Meds now help players learn more quickly. (Not exactly thematically consistent, but if anyone has a better idea let me know) This is a way to remedy the long term growth of how long it takes between each available skill points.
  </div><div class='ms-3'>
    &#8226; Mining uranium without a Radiaton suit induces a penalty on learning consistent with gaining a skill point but applied each time you do this action without a radiation suit.
  </div><div class='ms-3'>
    &#8226; The clacks estate tax for when players rebirth has been upgraded from 10% to 50%.
  </div>
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUF FIX</span>
  Fixed an issue where if someone did an action then did a freelance or hired action and tried to automate, it would do the action instead of the freelance or hired action.



</div><div class='fw-bold mt-3'>
  04/17/22
</div><div class='ms-3 mb-1'>
  State Buy Orders can now be filtered and sorted.



</div><div class='fw-bold mt-3'>
  04/16/22
</div><div class='ms-3 mb-1'>
  Reorganized the contracts tab. It is now the market tab and has a better organization and filter process.
</div><div class='ms-3 mb-1'>
  Fixed an issue where when an error was returned that a contract had to be cancelled when you used it, the contracts weren't refreshed.
</div><div class='ms-3 mb-1'>
  Made it to where when doing a buy contract for items, it will automatically buy items from any relevant sell item contract, and vice versa.

</div><div class='fw-bold mt-3'>
  04/15/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Players can now lease buildings so they can do an action by leasing out the action.
</div><div class='ms-3 mb-1'>
  You can sort land page and filter by owner as well.
</div><div class='ms-3 mb-1'>
  You can now filter the history page to just see a certain type of history status.
</div><div class='ms-3 mb-1'>
  Players can now access freelanced and hireble contract actions in actions area under labor tab.

</div><div class='fw-bold mt-3'>
  04/14/22
</div><div class='ms-3 mb-1'>
Players can now freelance themselves out to construct or repair buildings. (This is really great because it means players don't have to assign points in Construction in order to build buildings.)
</div><div class='ms-3 mb-1'>
  Players can now see the skill level of someone doing a freelance action.



</div><div class='fw-bold mt-3'>
  04/13/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  You can now automate contract hiring and freelance actions. (Super excited this is implement and it was way easier than I thought. Possibly buggy.)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Players can now convert wood to coal.



</div><div class='fw-bold mt-3'>
  04/12/22
</div><div class='ms-3 mb-1'>
  Added jackhammer, chainsaws, cars, tractors and bulldozers.
</div><div class='ms-3 mb-1'>
  Now when you select which building you wanna build, it'll bring up a little box that shows how much that building will cost before you click build.


</div><div class='fw-bold mt-3'>
  04/11/22
</div><div class='ms-3 mb-1'>
  Added action listing to help page.

</div><div class='fw-bold mt-3'>
  04/10/22

</div><div class='ms-3 mb-1'>
  Made Radiation Suits have 1000 uses instead of 100.
</div><div class='ms-3 mb-1'>
  Changed opacity of status bar so you can't read text behind it.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Fixed a bug where players who were buying an amount larger than what a player currently has would cancel the contract even if that player still had items remaining.

</div><div class='fw-bold mt-3'>
  04/10/22
</div><div class='ms-3 mb-1'>
  Farming, Engineering, Smelting, Toolmaking and Mining are now general skills that can unlock the more specific skills. The specific skills act as a bonus.
</div><div class='ms-3 mb-1'>
  There is now a land bonus for the number of lands you have when mining (mountains), planting & harvesting rubber (jungle) or mining sand (desert).
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Fixed a bug where if you had an Electric Arc Furnace and a Small/Large Furnace with enough Electricity but not enough Coal it wasn't returning the appropriate error message.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Fixed a bug where your previously unlocked skills weren't being locked after rebirth.
</div><div class='ms-3 mb-1'>
  Fixed an oversight where building did not decrement your work hours.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Fixed bug where Radiation Suit was not being used.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Fixed bug where Radiation Suit wasn't equipping from item list but could still be equipped from equipment menu.


</div><div class='fw-bold mt-3'>
  04/09/22
</div><div class='ms-3 mb-1'>
  Decrement brings a skill to 0 now instead of what it did previously.
</div><div class='ms-3 mb-1'>
  Satelittes now take 1 CPU instead of 10.
</div><div class='ms-3 mb-1'>
  Solar Panels now take 100 Silicon instead of 1000.
</div><div class='ms-3 mb-1'>
  Electricity cost for a Robot is now 100,000, not 1,000. The CPU cost was reduced from 100 to 10.
</div><div class='ms-3 mb-1'>
  Players can now create a setting for the account for sound on/off, eat food, and using meds. (Still need to apply this to contract work)

</div><div class='fw-bold mt-3'>
  04/08/22
</div><div class='ms-3 mb-1'>
  Increased Max Skill Points from 20 to 30.
</div><div class='ms-3 mb-1'>
  When you rebirth, you will no longer keep going up in work hours but you will receive a 10% clacks penalty.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Fixed a bug where players couldn't get smelt if they had a large furnance and an Electric arc furnace but ran out of 1000 input ore or Electricity, despite having a large furance.
</div><div class='ms-3 mb-1'>
  Made Nuclear Power Plants 10x more expensive. (I was able to get it way too early. [Earlier than Solar Power Plants])
</div><div class='ms-3 mb-1'>
  Players will now see a warning when their land is experiencing a hostile takeover.
</div><div class='ms-3 mb-1'>
  There will now be a sound when new skill points become available or automation stops.




</div><div class='fw-bold mt-3'>
  04/07/22

</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Can now decrement skills, but you don't get everything back. You only ever get 1 skill point back.

</div><div class='fw-bold mt-3'>
  04/06/22
</div><div class='ms-3 mb-1'>
  Added all items and how they're created to help page.
</div><div class='ms-3 mb-1'>
  Fixed a minor bug where your history tab was getting updated twice when doing a contract.
</div><div class='ms-3 mb-1'>
  Coal Power Plants now generate 1000 Electricity for 1000 Coal instead of 100 Electricity.



</div><div class='fw-bold mt-3'>
  04/05/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Fixed an issue where Rebirth wasn't letting you reset with skill. (It was actually an issue with the game continuing after displaying an error to the user.) THANkS MISTKAEN / ONE
</div><div class='ms-3 mb-1'>
  Hiring can now be priced per skill level instead of just per action.
</div><div class='ms-3 mb-1'>
  Changed how land tab looks to look better on mobile and be more streamlined.
</div><div class='ms-3 mb-1'>
  Changed how building costs page looks to be more streamlined and easier to use
</div><div class='ms-3 mb-1'>
  When The State buy orders used to be fulfilled, the next buy order put in would be put in for twice the quantity but the same price per unit. Now that new price is dropped down a bit.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Building slots were not getting refreshed on the front end when you did an action.
</div><div class='ms-3 mb-1'>
  Changed auto-stop of automating tasks from % to just the number of work hours. (seems simpler)
</div><div class='ms-3 mb-1'>
  Removed state tab. Merged into capital tab.
</div><div class='ms-3 mb-1'>
  You can now hide fields in the buildings tab.

</div><div class='fw-bold mt-3'>
  04/02/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Fixed an issue with Rebirth where it wasn't letting you reset your skill. THANKS MISTAKEN / ONE
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Fixed an issue where the buy-x quantity would be displayed even if you didn't have the necessary item capacity THANKS MISTAKEN / ONE


<div class='fw-bold mt-3'>
  04/01/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Smelting giving a false error message when you should have been able to do it. (Still really buggy) THANKS MISTAKEN / ONE
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Believe I fixed issue where doing labor & reproduction contracts didn't update your work hours in the browser afterwards.THANKS MISTAKEN / ONE
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Fixed an issue where if you had children it was messsing with your ability to do Actions
</div><div class='ms-3 mb-1'>
  Players should not be able to transfer Nuclear Waste to another player now.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Food Factory. (Players can now use Electricity instead of Coal and cook 100 of a food source at a time.)
</div><div class='ms-3 mb-1'>
  Status and error messages will now flash bold when gettting updated. (kinda overdue, but makes it real obvious how long the delay is between client and server)
</div><div class='ms-3 mb-1'>
  Players can now choose if they take meds when doing an action.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>BUG FIX</span>
  Broke hire, freelance and robot action system when I implemented the new ability to toggle if you use meds when doing an action. Fixed that.

</div><div class='fw-bold mt-3'>
  03/31/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Solar Power Plants (Allows you to harvest electricity without any input resources)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Electric Arc Furnace (Allows you to smelt 1000 resources at a time using Electricity - might break things - kinda hacked together to get this to work - lemme know of any bugs)
</div><div class='ms-3 mb-1'>
  Changed status bar to be readable if you're midway down the page

<div class='fw-bold mt-3'>
  03/30/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Robots (Players can now automate other skill level 1 actions using Robots even if they don't have that skill - in order to program it, you do need the skill first though)
</div><div class='ms-3 mb-1'>
  Dropped the increase in price for unfulfilled buy orders from The State from a 50% increase to a 10% increase.
</div><div class='ms-3 mb-1'>
  You can now reprogram Robots.



</div><div class='fw-bold mt-3'>
  03/28/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented a requested feature where players can set when they stop automating a task.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Nuclear Waste & Nuclear Power Plant. (Crazy amount of electricity created in Nuclear Power Plant - but Nuclear Waste cannot be dumbed [will eventually be able to reduced with your centrifuge])
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Satellite (100x the minimum chance of exploring)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Robots & Robotics Lab (will eventually be able to program them to do tasks automatically)
</div><div class='ms-3 mb-1'>
  Changed status messages for selling to make it more apparent how many you are selling and how much clacks you have after.


</div><div class='fw-bold mt-3'>
  03/27/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Added auto-bribe in land tab so players can automatically pay bribes
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented NanoMeds in a Nano Lab (Your Work Hours will not go down after an action.)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Plutonium in a Centrifuge (Will eventually be used in a Nuclear Power Plant to generate power)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Rocket Engines & Propulsion Labs(used to create Space Shuttles or Satellites)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Genetic Material & Clone Vats. (Genetic Material will eventually be used to create Clones)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>

  Players were able to lease land that they already owned. Fixed that.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  When dumping items, the items list would glitch.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed an issue where if you took NanoMeds, it would say, "You didn't use any work hours" but if you were mining uranium without a radiation suit, it also said, "You lost [ x ] hours".
</div><div class='fw-bold mt-3'>
  03/26/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Uranium Ore. (Will eventually be able to be converted to Plutonium to power Nuclear Power Plants)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Corpse. (Will eventually be turned into Genetic Material in a Clone Vat)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented HerbMeds. (Gives 1 in 10 chance to not use Work Hours when doing an Action)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Bio Material & BioMeds. (BioMeds give a 1 in 5 chance to not use Work Hours when doing an Action)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Players can now lease out access to their land. (Example: So players who don't have land or access to a forest to chop trees now have access if they're willing to pay.)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Messed up on leases. Contract owner wasn't getting paid properly.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed this: 'When automation ends, start automation button isn't disabled.'
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed issue where after accepting lease, all of the other options on contracts would disappear.
</div><div class='ms-3 mb-1'>
  Players can now dump specific amounts of things instead of the entire quantity of an item.

</div><div class='fw-bold mt-3'>
  03/25/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Plant X & Herbal Greens. Plant X will be used to create Bio Material. (This will be useful in creating Bio Material, which will have applications later in medicine and cloning.) Herbal Greens will be used in the first level of medicine. (Herbal Medicine)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Nano Lab and Nanites. (Nanites will eventaully be how you can create the highest level of medicine NanoMeds)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented CPU Fabrication Plant & CPU. (CPU will be used in Computers, Robots & Satellites. It's also used in building Nano Lab. [Maybe more.])
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Solar Panels. Will eventually be used in Satellites and Solar Power Plants.
</div><div class='ms-3 mb-1'>
  Updated yellow button tool tip text for new actions
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed how Last Action button wasn't being disabled when there was no last action.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed how chat wasn't filtering properly.

</div><div class='fw-bold'>
  03/23/22
</div><div class='ms-3 mb-1'>
  When there is a new building available to build, it will display a message to let the player know.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  There is now a new land type: Sand (1 in 10 Exploration chance) and players can mine Sand from that land type.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Chem Lab, and being able to make Carbon Nanotubes, Silicon, Tires & Radiation Suit items as part of the Chemical Engineering Skill.

</div><div class='fw-bold'>
  03/22/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed a bug where rebirth wasn't implementing.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed land filtering so it won't snap back when doing actions (finally)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Added Child Prodigy perk, which increases starting available skill points by 1 at the expense of 10 max skill points. (Requires a minimum of 25 max skill points)
</div><div class='ms-3 mb-1'>
  Children now require addiitonal food upkeep. (+1 food per action per each child)
</div><div class='ms-3 mb-1'>
  Added an explanation for the Rebirth perks
</div><div class='ms-3 mb-1'>
  Farming Rubber now has the appopriate icon in its skill listing to indicate that it requires land
</div><div class='ms-3 mb-1'>
  Created a way to go directly to parcel # in land tab from contracts tab
</div><div class='ms-3 mb-1'>
  Construction contracts will now rebuild a building if possible instead of failing if the building already exists.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Machining skill, Machine Shop building, Diesel Engines, Gasoline Engines, Gas Motors, & Electric Motors. (Motors are used for Jackhammers and Chainsaws. Engines are used for Tractors.)

</div><div class='fw-bold'>
  03/21/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed a bug where hostile takeovers weren't completing.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed an issue where hostile takeovers were initiating with an option of 0 instead of 1.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed land filtering so it won't snap back when doing actions (hopefully)
</div><div class='ms-3 mb-1'>
  Added name of person doing a hostile takeover to land tab
</div><div class='ms-3 mb-1'>
  You can now filter chat to just see chat messages.
</div><div class='ms-3 mb-1'>
  Now an integrity check is done when land is transferred.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed issue where repairs weren't filtering in contracts.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed the ability for one player to pull up multiple windows and spam an action indeifinitely. (Now there's like a 1 second delay between actions) (Thanks, mistaken314 / ONE!)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed player being able to spam rebirth by f5'ing the rebirth post. (Thanks, mistaken314 / ONE!)
</div><div class='ms-3 mb-1'>
  Updated yellow action buttons to explain why you can't do an action when you click on them.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Added Children & Books. Rebirth is now re-done. You will always get 10% more
  work hours, which will slow down how quickly you gain new available skill
  points.  Now Genius & Legacy perks require Books & Children respectively.
  Legacy also just simply lets you get reborn with a specified skill's level.
</div><div class='fw-bold'>
  03/14/22
</div><div class='ms-3 mb-1'>
  When selling land through a contract, you can now see what type of land it is without having to go to the land tab.
</div><div class='ms-3 mb-1'>
  Players can now see how long ago chat messages and history statuses were.
</div><div class='ms-3 mb-1'>
  When players sell land, that will become its new valuation.
</div><div class='ms-3 mb-1'>
  Actions that are not available are marked in yellow and players can click on them to find out why they aren't able to do them. (might be a lil buggy)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed a bug to where hostile takeover page wasn't reloading after a bid and clicking on the land page led to hte takeover dialog button.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented Rubber Plantation and Rubber. (To eventually be used to make rubber which will be used to make tractors - to harvest 10 fields at a time)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Players are now able to refine oil into Jet Fuel, Diesel and Gasoline. (Will eventually be used as fuel for tractors - another other things)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Fixed bug in skills where if you were automation, skills section went all crazy.
</div><div class='ms-3 mb-1'>
  Removed Meat and Food from The State buy orders to remove the price floor so that new players can hopefully buy and sell it.
</div><div class='fw-bold'>
  03/13/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span>
  Implemented land protection and hostile takeovers. (Hopefully, that'll rememdy the land situation.)
</div><div class='fw-bold'>
  03/12/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  Changed it to where players can now have multiple contracts of the same category.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix</span>
  'Pump oil' action button now disabled when it is not a valid action.
</div><div class='ms-3 mb-1'>
  Fixed contract filtering and sorting so it's not flittin about anymore. Also added to where you can show only your contracts.
</div><div class='ms-3 mb-1'>
  Changed formatting of contracts page to color code the buttons that will cost money (red) and give you money (blue). Cancel is now yellow.
</div><div class='ms-3 mb-1'>
  Players can now see how old The State buy orders are.
</div>

<div class='fw-bold'>
  03/11/22
</div><div class='ms-3 mb-1'>
    <span class='fw-bold'>NEW</span>
    Oil Wells can now be built and Oil can be created.
</div><div class='ms-3 mb-1'>
    <span class='fw-bold'>Bug Fix:</span>
    Fixed issue to where tools were not able to be sold to The State.
</div><div class='ms-3 mb-1'>
    <span class='fw-bold'>Bug Fix:</span>
    Fixed issue where players couldn't smelt copper if they had a Large Furnace.
</div><div class='ms-3 mb-1'>
    <span class='fw-bold'>Bug Fix:</span>
    Fixed a bug (hopefully) where Jungle wasn't coming up when people explored.
</div><div class='ms-3 mb-1'>
  Players can now have multiple warehouses.
</div><div class='ms-3 mb-1'>
    <span class='fw-bold'>Bug Fix:</span>
    Fixed a bug where active Contracts were being deleted when players reset their characters.
</div><div class='ms-3 mb-1'>
    <span class='fw-bold'>Bug Fix:</span>
    Fixed a bug where buy orders from The State were not increasing when players hadn't fulfilled them for 24 hours.
</div><div class='ms-3 mb-1'>
    Now when players reset, they will lose everything EXCEPT their clacks.
    (The original idea behind losing everything was so players wouldn't do actions, reset, do more actions, reset, etc. but money is getting really, really hard for new players to come by and I'm going to be making changes to the Rebirth some time in the future, so why not?)
</div>

<div class='fw-bold'>
  03/10/22
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span> Added new terrain type: jungle. (idea: rubber plantation->rubber->tires->vehicles)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span> Added Copper Ore & Copper Ingots (idea: You need copper to conduct electricity, right?)
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span> Added Coal Power Plant. Electricity can now be produced with the Electrical Engineering skill.
</div><div class='ms-3 mb-1'>
  When a contract is a fulfilled, the contractor and the agent fulfilling the contract will now have their history tab updated.
</div>

<div class='fw-bold'>
  03/09/22
</div><div class='ms-3 mb-1'>
  Added rebuild button to buildings section (so players don't have to cycle between labor and capital tab)
</div><div class='ms-3 mb-1'>
  Added filters to contract page.
</div><div class='ms-3 mb-1'>
  Added changelist (this) and <a href='/help'>help</a> page.
</div><div class='ms-3 mb-1'>
  Now displaying how much resources you currently have on the building costs page so you know how much more you need.
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>Bug Fix:</span> Fixed a bug where if you repaired,
  it could actually bring it to a lower condition than it was  previously.
  (from 90% to 60%) Also, another bug where 90% was displaying as 900%.
</div><div class='ms-3 mb-1'>
  history is now limited to the last 100 entries
</div><div class='ms-3 mb-1'>
  Reduced Hunting base yield from 3 to 2.
</div><div class='ms-3 mb-1'>

  Reduced base Wheat Yield slightly. From 15 down to 10.
</div><div class='ms-3 mb-1'>

  Reduced cooking so that the production ratio  from food source to Food is now 1:2 instead of 1:3
</div><div class='ms-3 mb-1'>
  <span class='fw-bold'>NEW</span> Created Flour, Flour Milling Skill, Handmill & Gristmill. Players cannot cook Wheat. They must convert it to Flour and then cook it.
</div>

@endsection
