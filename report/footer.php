
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap-4.3.1/popper.min.js"></script>
<script src="../js/bootstrap-4.3.1/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/DataTables-1.10.20/datatables.min.js"></script>
<script type="text/javascript" src="../js/DataTables-1.10.20/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#requestsTable').DataTable({
            "language": {
            "url": "../../js/DataTables-1.10.20/Russian.json"
            },
           // "order": [[ 0, "asc" ]],
            "pageLength": 25
        });
    });
</script>
</body>
</html>