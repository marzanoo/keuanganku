<form action="<?=site_url('auth/process2')?>" method="post" class="row g-3 needs-validation" novalidate>

                    <div class="col-12">
                      <label for="NIP" class="form-label">NIP</label>
                        <input type="text" name="f_NIP" class="form-control" id="NIPmu" required>
                        <div class="invalid-feedback">Masukkan NIP Anda</div>  
                    </div>
                    
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit" name="login">Login</button>
                    </div>

                  </form>