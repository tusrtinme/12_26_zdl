$(function(){$("#js-screen").click(goods_list.screen),$("#js-backs").click(goods_list.backs),$(".goods_list_dialog_btn.lbtn").click(goods_list.backs),$(".goods_list_screen_list").on("click",".js-list-screen",goods_list.list_screen),$("#goods_switcher").click(goods_list.switcher),$(".goods_list_dialog_list").on("click",".js-screen-checked",goods_list.screenchecked),$(".goods_list_dialog_left").on("click",".js-checked",goods_list.lchecked),$(".goods_list_dialog_right").on("click",".js-checked",goods_list.rchecked)});var goods_list={screen:function(){$("#js-dialog").toggle(),$("#js-backs").toggle(),$(".webkit_content").toggleClass("hide")},backs:function(){$("#js-dialog").hide(),$("#js-backs").hide(),$(".webkit_content").removeClass("hide")},switcher:function(){$(".js-lengthways")[0]?$(".js-lengthways").addClass("js-crosswise").removeClass("js-lengthways"):$(".js-crosswise").addClass("js-lengthways").removeClass("js-crosswise")},list_screen:function(){$(".js-list-screen").removeClass("active"),$(this).addClass("active")},screenchecked:function(){$(this).hasClass("active")?$(this).removeClass("active"):$(this).addClass("active")},lchecked:function(){$(".goods_list_dialog_left").find(".js-checked").removeClass("active"),$(this).addClass("active")},rchecked:function(){$(".goods_list_dialog_right").find(".js-checked").removeClass("active"),$(this).addClass("active")}};