<div class="modal fade" id="modalEstimate" tabindex="-1" role="dialog" aria-labelledby="modalEstimateLabel"
  aria-hidden="true">
    <div class="modal-dialog modal-estimate" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEstimateLabel">{$product->name[$id_lang]}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="fullname" class="col-form-label required">{l s='Nombre' mod='ht_estimate'}</label>
            <input type="text" name="fullname" class="form-control" id="fullname"
              placeholder="{l s='Nombre' mod='ht_estimate'}">
          </div>
          <div class="form-group">
            <label for="phone" class="col-form-label required">{l s='Telefono' mod='ht_estimate'}</label>
            <input type="text" name="phone" class="form-control" id="phone"
              placeholder="{l s='Telefono' mod='ht_estimate'}">
          </div>
          <div class="form-group">
            <label for="email" class="col-form-label required">{l s='Email' mod='ht_estimate'}</label>
            <input type="email" name="email" class="form-control" id="email"
              placeholder="{l s='Email' mod='ht_estimate'}">
          </div>
          <div class="form-group">
            <label for="description" class="col-form-label required">{l s='Descripción' mod='ht_estimate'}</label>
            <textarea 
              placeholder="{l s='Descripción' mod='ht_estimate'}"
              id="description"
              name="description" class="form-control" title="{l s='Descripción' mod='ht_estimate'}"
              cols="30" rows="10" aria-required="true"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button 
           id="submitEstimate"
           type="submit" 
           class="btn btn-primary"
           data-url="{$url}"
           data-product-id="{$product->id}"
           >
           {l s='Enviar' mod='ht_estimate'}
          </button>
        </div>
      </div>
    </div>
</div>