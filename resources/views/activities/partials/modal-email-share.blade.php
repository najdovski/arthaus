<div class="modal fade" id="modal-email-share" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold">Share activities by email</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('email-share') }}" method="POST" class="js--validate row justify-content-end">
          @csrf
          <input type="hidden" name="email-started-at">
          <input type="hidden" name="email-finished-at">
          <input type="hidden" name="email-current-page">
          <div class="form-group col-12">
            <label for="email">Enter email:</label>
            <input name="email" class="form-control" type="email" required>
          </div>
          <div class="form-group col-5">
            <button type="submit" class="btn btn-block btn-success text-white">Send</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
