  <form class="form-contact">
    <h2>Contact us</h2>
    <br>
    <label for="Name">Name</label>
    <input type="text" id="name">
    <label for="email">Email Address</label>
    <input type="email" id="email">
    <label for="phone">Phone Number</label>
    <input type="tel" id="phone">
    <label for="message">Message</label>
    <textarea id="message">THIS DOES NOTHING YET
      <?php echo $jr_safeArray['pgName']; ?>
    </textarea>
    <button class="btn-red">
      <h3>Send</h3>
    </button>
  </form>
