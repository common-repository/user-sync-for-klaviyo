(function ($) {
	'use strict';
	$(document).ready(function () {
		var swk_settings = window.swk_settings || [];
		var swk_run_sync = true;
		$("#swk-start-sync").on("click", function () {
			// Define data to be sent in the request
			swk_settings.sync_users = {
				action: 'sync_all_users',
				fields: 'ID',
				paged: 1,
				number: get_batch_size(),
				total_users: swk_settings.total_users,
				nonce: swk.nonce,
			};
			$("#swk-progress-bar-container").slideDown();
			$(this).hide();
			$("#swk-stop-sync").show();
			document.getElementById('swk-status-text').innerText = 'Running';
			swk_run_sync = true;
			ajax_sync_users(swk_settings.sync_users);
		});

		$("#swk-stop-sync").on("click", function () {
			if (confirm('Are you sure you want to stop the sync?')) {
				swk_run_sync = false;
				document.getElementById("swk-loader").style.display = 'none';
				$(this).hide();
				$("#swk-start-sync").show();
				document.getElementById('swk-status-text').innerText = 'Stopped';
			}
		});

		$(".key-reveal").on('click', function () {
			const input = $(this).prev();
			const isPassword = input.attr('type') === 'password';
			const iconClass = isPassword ? 'dashicons-visibility' : 'dashicons-hidden';
			const inputType = isPassword ? 'text' : 'password';
			
			input.attr('type', inputType);
			$(this).removeClass('dashicons-hidden dashicons-visibility').addClass(iconClass);
		  });

		$('.swk-toggle-header').on('click', function(){
			let content = $(this).next('.swk-toggle-container');
			let dashicon = $(this).find('.dashicons');
			if(content.is(':visible')){
				content.hide();
				dashicon.addClass('dashicons-arrow-right').removeClass('dashicons-arrow-down')
			}else{
				dashicon.addClass('dashicons-arrow-down').removeClass('dashicons-arrow-right')
				content.show();
			}
		})

		function get_batch_size()
		{
			var batchSizeInput = document.getElementById('swk-batch-size');
            var batchSize = batchSizeInput ? batchSizeInput.value : 250;
			// just stops any funny business
			if(batchSize < 1){
				batchSize = 10
			}
			swk_settings.number = batchSize;
			return batchSize;
		}
		  

		function ajax_sync_users(data) {
			if (!swk_run_sync) return;

			$.ajax({
				url: swk.ajaxurl,
				type: 'POST',
				dataType: 'json', // Set expected response data type
				data: data, // Data to be sent in the request
				success: function (response) {
					// Handle successful response
					console.log(response);
					if (response.sync_status == 'processing') {
						ajax_update_progress_bar(swk_settings.sync_users.paged, (swk_settings.total_users / swk_settings.number))
						swk_settings.sync_users.paged = response.next_paged
						ajax_sync_users(swk_settings.sync_users);
					} else if(response.sync_status == 'error') {
						ajax_update_progress_bar(1, 1, false, true, response.error_message)
					}else{
						ajax_update_progress_bar(1, 1, true, false, "All done!")
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					// Handle error
					console.error('Error:', textStatus, errorThrown);
					ajax_update_progress_bar(1,1,false,true,errorThrown);
				}
			});
		}

		function ajax_update_progress_bar(progress, total_pages, complete = false, error = false, statusText = "Running") {
			// Check if sync is running - syncs run async so the text might not always be correct
			statusText = swk_run_sync ? statusText : "Stopped";
		
			// Select DOM elements
			const progressBar = document.getElementById("swk-progress-bar");
			const progressText = document.getElementById("swk-progress-text");
			const progressLoader = document.getElementById("swk-loader");
			const progressStatus = document.getElementById("swk-status-text");
		
			// Update progress status
			progressStatus.innerHTML = statusText;
		
			// Handle error case
			if (error) {
				progressBar.style.width = "100%";
				progressBar.style.backgroundColor = "red";
				progressText.innerText = "";
				progressLoader.style.display = "none";
				return;
			}
		
			// Handle complete case
			if (complete) {
				progressBar.style.width = "100%";
				progressBar.style.backgroundColor = "green";
				progressText.innerText = "";
				progressLoader.style.display = "none";
			} else {
				// Update progress bar
				const progressPercent = (progress * 100) / total_pages;
				progressBar.style.width = `${progressPercent}%`;
				progressBar.style.backgroundColor = "#428bca";
				progressText.innerText = `${progress * swk_settings.number} of ${swk_settings.total_users} users synced to Klaviyo`;
			}
		}
		
	});

})(jQuery);
