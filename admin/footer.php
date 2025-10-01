</div> 

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Initializing DataTables on all tables with class 'datatable'
    $(document).ready(function () {
        $('.datatable').DataTable({
            // ตัวเลือกเพิ่มเติม (Optional settings)
            "language": {
                "url": "//cdn.datatables.net/plug-ins/2.0.8/i18n/th.json" // ภาษาไทย
            }
        });
    });
</script>

</body>
</html>