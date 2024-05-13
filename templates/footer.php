</main>
<footer>
  <div class="d-flex align-items-center justify-content-center" style="height: 60px; background: rgb(33, 37, 41); color: white;">
    <b5>&copy; Derechos Reservados UPDS
      <b5 />
  </div>
</footer>
<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
</script>

<script>
  $(document).ready(function() {
    $("#tabla_id").DataTable({
      "pageLength": 5,
      lengthMenu: [
        [3, 5, 10, 25, 50],
        [3, 5, 10, 25, 50]
      ],
      "language": {
        "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
      }
    });
  });
</script>
<script>
  function borrar(ID) {
    Swal.fire({
      title: '¿Esta seguro de borrar el registro?',
      text: '¡Una vez borrado no se puede recuperar!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Si, elimínelo'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location = "index.php?txtID=" + ID;
      }
    })
  }
</script>

</body>

</html>