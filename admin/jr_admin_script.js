/*
 * Caramel (http://caramel.ga)
 * Copyright 2015, All Rights Reserved
 * GPL v2 License
 */
 $(document).ready(function(){var n=$(".nav");n.on("click",".collapse",function(){$(this).parents("ul").toggleClass("open")}),n.on("click",".dropdown",function(n){n.preventDefault(),$(this).parents("li").find("> ul").toggleClass("open")}),$(".dismiss").click(function(){$(this).closest("#note").fadeOut(500,function(){$(this).remove()})}),$(window).resize()});

