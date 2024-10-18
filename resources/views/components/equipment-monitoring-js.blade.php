<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('select[name="equipment_id"]').addEventListener('change', function(e) {
            const equipmentId = e.target.value;

            if (equipmentId) {
                fetch(`/api/equipment/${equipmentId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('input[name="status"]').value = data.status;
                    });
            }
        });

        document.querySelector('input[name="status"]').addEventListener('change', function(e) {
            const status = e.target.value;
            const equipmentId = document.querySelector('select[name="equipment_id"]').value;

            if (equipmentId) {
                fetch(`/api/equipment/${equipmentId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        status
                    })
                });
            }
        });
    });
</script>