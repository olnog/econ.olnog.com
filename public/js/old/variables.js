const startingWorkHours = 1000, startingSkillPoints = 6, remainingSkillPoints =  14

var actions = [
  "chop-tree", //
  "cook-meat", //
  "explore",
  "gather-stone", //
  "gather-wood", //
  "hunt", //
  "mill-log", //
  "make-iron-axe",
  "make-iron-pickaxe",
  "make-iron-saw",
  "make-stone-axe",
  "make-stone-pickaxe",
  "make-stone-saw",
  'mine-coal',
  'mine-iron-ore',
  "mine-stone", //
  "smelt-iron-ore",
]

var availableSkillPoints = 6
var allocatedSkillPoints = 0


buildingDurabilityCaption =  ['badly built', 'poorly built', 'average built', 'well-built', 'greatly built', 'excellently built']

var workHoursLearning = 0
var buildings = {}

var buildingCosts = {
  campfire: {wood: 10},
  kitchen: {stone: 100, wood: 100},
  largeFurnace:  {stone: 1000},
  mine: {wood: 1000},
  sawmill: {wood: 1000, iron: 10, stone: 100},
  smallFurnace: {stone: 100},
  warehouse: {wood: 1000, stone: 100}
}
var buyOrders = {
  /*
  logs : {quantity: 10, cost: 10},
  meat: {quantity: 10, cost: 10},
  stone : {quantity: 10, cost: 10},
  wood : {quantity: 10, cost: 10}
  */

}

var clacks = 0

var equipped = {
  type: null,
  caption: 'nothing',
  uses: 0,
  totalUses:0
}
var foundLands = 0


var items = {
  coal: 0,
  food: 0,
  iron: 0,
  ironOre: 0,
  logs: 0,
  meat: 0,
  stone: 0,
  wood: 0
}

var land = {
  forest: 1,
  mountains: 1,
  plains: 0
}
var qualityCaption = [null, "horrible", "poor", 'average', 'good', 'excellent']

var skills = {
  construction: {caption: "Construction", rank: 0},
  //contracting: {caption: "Contracting", rank: 0},
  cooking: {caption: "Cooking", rank: 0},
  exploring: {caption: "Exploring", rank: 0},
  hunting: {caption: "Hunting", rank: 0},
  lumberjacking: {caption: "Lumberjacking", rank: 0},
  miningStone: {caption: "Mining (stone)", rank: 0},
  miningIron: {caption: "Mining (iron ore)", rank: 0},
  miningCoal: {caption: "Mining (coal)", rank: 0},
  sawmilling: {caption: "Sawmilling", rank: 0},
  smeltingIron: {caption: "Smelting (iron)", rank: 0},
  toolmakingStone: {caption: "Toolmaking (stone)", rank: 0},
  toolmakingIron: {caption: "Toolmaking (iron)", rank: 0}
}
workHours = 1000
