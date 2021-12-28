		</div> <!-- END: PAGE CONTAINER -->
		
		<!-- BEGIN CORE PLUGINS -->
		<!--[if lt IE 9]>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/global/plugins/excanvas.min.js"></script> 
		<![endif]-->
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/jquery-migrate.min.js" type="text/javascript" type="text/javascript" ></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript" type="text/javascript" ></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/jquery.easing.min.js" type="text/javascript" ></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/reveal-animate/wow.js" type="text/javascript" ></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/demos/default/js/scripts/reveal-animate/reveal-animate.js" type="text/javascript" ></script>
		<!-- END: CORE PLUGINS -->

		<!-- BEGIN: LAYOUT PLUGINS -->
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/fancybox-master-3.2.10/dist/jquery.fancybox.js" type="text/javascript"></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/smooth-scroll/jquery.smooth-scroll.js" type="text/javascript"></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/typed/typed.min.js" type="text/javascript"></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/slider-for-bootstrap/js/bootstrap-slider.js" type="text/javascript"></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/js-cookie/js.cookie.js" type="text/javascript"></script>
		
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
		
		<!-- END: LAYOUT PLUGINS -->

		<!-- JSGRID -->
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/jsgrid-1.5.3/dist/jsgrid.min.js" type="text/javascript"></script>

		<!-- SELECT2 -->
		

		<!-- BEGIN: PAGE SCRIPTS -->
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/moment.min.js" type="text/javascript"></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/demos/default/js/scripts/pages/datepicker.js" type="text/javascript"></script>
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/plugins/sweetalert/sweetalert2.min.js"></script>
		<!-- END: PAGE SCRIPTS -->

		<!-- BEGIN: CUSTOM SCRIPTS -->
		<script src="<?= Snl::app()->config(TRUE)->theme_url ?>assets/local/js/local.js" type="text/javascript"></script>
		
		<!-- END: CUSTOM SCRIPTS -->

		<script>
		    $(document).ready(function() {    
		        // App.init(); // init core    
		    });
		</script>
		<!-- END: THEME SCRIPTS -->

		<!-- BEGIN: PAGE SCRIPTS -->
		<script>
			$(function () {
				if($('#product_pagination').length > 0) {
					window.pagObj = $('#product_pagination').twbsPagination({
			            totalPages: totalPages,
			            visiblePages: visiblePages,
			            hideOnlyOnePage: true,
			            href: true,
			            pageVariable: 'page',
			            onPageClick: function (event, page) {
			                // console.info(page + ' (from options)');
			            }
			        }).on('page', function (event, page) {
			            // console.info(page + ' (from event listening)');
			        });
				}

				if($('.table-datatables')) {
					$('.table-datatables').each(function() {
						$(this).dataTable({
							"language": {
							    "emptyTable":     "Tidak ada order",
							    "info":           "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
							    "infoEmpty":      "Menampilkan 0 s/d 0 dari 0 data",
							    "infoFiltered":   "",
							    "lengthMenu":     "Menampilkan _MENU_ data",
							    "loadingRecords": "Loading...",
							    "processing":     "Processing...",
							    "search":         "Cari:",
							    "zeroRecords":    "Data tidak ditemukan",
								"paginate": {
							        "first":      "Pertama",
							        "last":       "Terakhir",
							        "next":       "&#8594;",
							        "previous":   "&#8592;"
							    },
							},
						});
					});
				}
		    });
		</script>

	</body>
</html>