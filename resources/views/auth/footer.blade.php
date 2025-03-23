<footer id="site-footer" class="py-4" style="display: none;">
  <div class="container text-center">
    <h5>Box Office Opening Hours</h5>
    <p class="mb-1">Box Office (in person) Open 2-hrs pre shows only.</p>
    <p class="mb-1">Access Line (Mon - Fri) 9am - 6pm, Sat/Sun 10am - 6pm: <a href="tel:01618762183">0161 876 2183</a></p>
    <p class="mb-1">Groups Line: Mon-Fri 9am-6pm: <a href="tel:01618762003">0161 876 2003</a></p>
    <p class="mb-0"><a href="#">FAQs</a></p>
  </div>
</footer>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Show registration form after 3 seconds
    setTimeout(() => {
      const registrationModal = document.getElementById("auth-modal")
      const footer = document.getElementById("site-footer")

      registrationModal.style.display = "flex"
      registrationModal.classList.add("fade-in")

      footer.style.display = "block"
      footer.classList.add("fade-in")

      // Add a class to the body to help with styling
      document.body.classList.add('modal-open');
    }, 400)

    // Also show form when register link is clicked
    document.getElementById("register-link").addEventListener("click", (e) => {
      e.preventDefault()
      const registrationModal = document.getElementById("registration-modal")
      const footer = document.getElementById("site-footer")

      registrationModal.style.display = "flex"
      registrationModal.classList.add("fade-in")

      footer.style.display = "block"
      footer.classList.add("fade-in")

      // Add a class to the body to help with styling
      document.body.classList.add('modal-open');
    })
  })
</script>
</body>
</html>