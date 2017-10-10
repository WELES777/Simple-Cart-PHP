$(document).ready(function ()
{
	
	$("span.addToCart").on("click", function ()
	{
		var id = $(this).attr("data-id");
		$.ajax({
			type: "GET",
			url: "../handlers/ajax.php?id=" + id + "&action=add"
		}).done(function ()
		{
			alert("Product have been added.");
		});
	});
	
	
	$("span.removeFromCart").on("click", function ()
	{
		var id = $(this).attr("data-id");
		$.ajax({
			type: "GET",
			url: "../handlers/ajax.php?id=" + id + "&action=remove"
		}).done(function ()
		{
			alert("Product has been removed.");
			location.reload();
		});
	});
	$("a.emptyCart").on("click", function ()
	{
		$.ajax({
			type: "GET",
			url: "../handlers/ajax.php?action=empty"
		}).done(function ()
		{
			alert("The cart has been emptied.");
			location.reload();
		});
	});
	
	$("#toPost").submit(function (event)
	{
		event.preventDefault();
		if (!$("input[name='transp']:checked").val())
		{
			alert("Please choose way of delivery");
		} else
		{
			$.ajax({
				type: "GET",
				url: "../handlers/ajax.php",
				data: $("#toPost").serialize(),
				success: function (data)
				{
					alert("The products where bought");
				},
				error: function (data)
				{
					console.log("Error: " + data);
				}
			});
			
			
		}
		
	});
	
	$(".checkord").click(function (event)
	{
		event.preventDefault();
		
		window.location.href = 'checkout.php#load-stuff';
		
	});
	
	$('input[type=radio][name=transp]').change(function ()
	{
		$.ajax({
			type: "GET",
			url: "../handlers/ajax.php?action=summ",
			data: $(".total").serialize(),
			success: function (data)
			{
				
				console.log(data);
				var cought = data.split(" ");
				var tp = cought[0];
				var ew = cought[1];
				
				$(".total_pay").text(tp);
				$(".ewall").text(ew);
			},
			error: function (data)
			{
				console.log("Error: " + data);
			}
		})
		
	});


// $(window).on("unload", function() {
//     $("#rating").on("submit", function(event) {
//         event.preventDefault();
//         $.ajax({
//                 type: "GET",
//                 url: "../handlers/ajax.php",
//                 data: $("#rating").serialize()
//             }
//             .done(function() {
//                 alert("Product has been rated.");

//             }));
//     });

// });
	$(".top").click(function ()
	{
		$.ajax({
			type: "GET",
			url: "../handlers/ajax.php",
			data: $("#rating").serialize()
		}).done(function ()
		{
			alert("Product has been rated.");
			
		});
	});
})
;