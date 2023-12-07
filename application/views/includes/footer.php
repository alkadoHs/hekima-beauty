
<script src="<?php echo base_url("node_modules/flowbite/dist/flowbite.min.js") ?>"></script>
<script src="<?php echo base_url("assets/js/datatable.js") ?>"></script>
<script src="<?php echo base_url("assets/js/select2.js") ?>"></script>
<script>
    new DataTable('#example');

    $(document).ready(function() {
        $('.select2').select2({
            theme: "classic",
            placeholder: "Search...",
            width: 'resolve'
        });
    });

    $(document).ready(function() {
        $('.selectSupplier').select2({
            theme: "classic",
            placeholder: "Select supplier...",
            width: 'resolve'
        });
    });


    $(document).ready(function() {
        $('#switchBranch').on('change', function() {
            var branchId = $('#switchBranch').val()
            var userId = "<?= $userId ?>";
            console.log("Data sent:", { branch: branchId, user: userId })

            $.ajax({
                url: "<?= site_url('user/switchBranch') ?>",
                type: 'POST',
                data: { branch: branchId, user: userId },
                success: function(response) {
                    location.reload();
                },
                error: function(error) {
                    console.error('Error updating data:', error);
                }
            });
        })
    })
</script>

</body>
</html>
