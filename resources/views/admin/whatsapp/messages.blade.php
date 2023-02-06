<div class="container pt-4">
  <form action="{{ route('whatsapp.send') }}" method="POST" enctype="multipart/form-data" id="whatsappForm">
    @csrf
    <div class="row">
      <div class="col-lg-3">
        <div class="card">
          <div class="card-header">
            Seleccionar destinatarios
            <div>
              <i>
                Seleccionados <span id="receivers-count">0</span>
              </i>
            </div>
          </div>
          <div class="card-body p-0" style="max-height: calc(100vh - 200px); overflow: auto;">
            <input id="extensionsFilter" placeholder="Buscar...">
            <ul class="list-group p-0">
              <li class="list-group-item">
                <input type="checkbox" name="select_all" id="checkbox-select_all" class="mr-3">
                <label for="checkbox-select_all">Seleccionar todos</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" name="owners_only" id="checkbox-owners_only" value="true" class="mr-3">
                <label for="checkbox-owners_only">Solo propietarios</label>
              </li>
              @foreach($extensions as $extension)
              <li class="list-group-item">
                <input type="checkbox" name="receivers[]" id="checkbox-{{$extension->id}}" class="mr-3 extension-check @if($extension->owner_phone) hasOwnerPhone @endif" value="{{ $extension->id }}" data-apto="{{ $extension->name }}">
                <label for="checkbox-{{$extension->id}}">{{ $extension->name }}</label>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="table-responsive mb-4">
          <textarea id="message" placeholder="Escribe un mensaje" rows="10" name="message" class="form-control mb-3" required></textarea>

          <div style="margin-bottom: -22px;" class="px-3">
            <div class="attachment-alert mb-2">
              Adjuntar solo Imagenes Y PDF
            </div>
            <div class="d-flex align-items-center">
              <input type="file" name="attachment" class="form-control d-none" id="attachmentInput">
              <button type="button" class="btn-round btn-attachment mr-3" onclick="document.querySelector('#attachmentInput').click()">
                <i class="material-icons" style="color: darkgray;">
                  attachment
                </i>
              </button>
              <div class="attachmentDetails"></div>
              <button type="button" class="btn btn-primary ml-auto" onclick="submitMessage()">
                Enviar
              </button>
            </div>
          </div>
        </div>

        @if( isset( $history ) )
        <div class="table-responsive">
          <table class="table">
            <thead>
              <th>
                Mensaje
              </th>
              <th>
                Cant.
              </th>
              <th>
                Fecha
              </th>
            </thead>
            <tbody>
              @foreach( $history as $batch )
              <tr>
                <td>
                  {{ $batch->message }}
                </td>
                <td>
                  {{ count( explode(',', $batch->receivers_numbers) ) }}
                </td>
                <td>
                  {{ Carbon\Carbon::parse($batch->created_at)->setTimeZone('GMT-5')->format('Y-m-d H:i:s') }}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @endif
      </div>

      <div class="col-lg-3" style="text-align: right">
        <div class="card mb-3">
          <div class="card-body">
            <div class="monospace">
              status
            </div>
            <div class="d-flex align-items-center justify-content-end">
              <span class="lightbulb"></span> Online
            </div>
            <div class="monospace">
              {{ auth()->user()->whatsapp_instance_id }}
            </div>
            <a href="{{ route('whatsapp.logout') }}" class="btn btn-danger">
              Cerrar sesión
            </a>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">
            <i class="material-icons">info</i> Recomendación
          </div>
          <div class="card-body">
            PHenlínea SAS recomienda el uso responsable del servicio de mensajería masíva
          </div>
        </div>

        <div class="card background-info">
          <div class="card-header">
            <i class="material-icons">info</i> Advertencia
          </div>
          <div class="card-body">
            PHenlínea SAS no se hace responsable del uso inapropiado del servicio de mensajería masíva
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

@section('scripts')
<script>
  const extensionsChecks = document.querySelectorAll('.extension-check')
  const ownersOnlyCheckbox = document.querySelector('#checkbox-owners_only')
  const selectAll = document.querySelector('#checkbox-select_all')
  const attachmentInput = document.querySelector('#attachmentInput')
  const whatsappForm = document.querySelector('#whatsappForm')
  const extensionsFilter = document.querySelector('#extensionsFilter')

  extensionsChecks.forEach(check => {
    check.addEventListener('change', updateCount)
  })

  if (selectAll) {
    selectAll.addEventListener('change', function(e) {
      if (!e.target.checked) return
      extensionsChecks.forEach(check => {
        check.checked = true;
        check.setAttribute('readonly', true)
      })
      updateCount()
    })
  }

  if (ownersOnlyCheckbox) {
    ownersOnlyCheckbox.addEventListener('change', function(e) {
      if (e.target.checked) {
        extensionsChecks.forEach(check => {
          check.checked = false
          check.style.display = 'none'
          check.parentElement.style.display = 'none'
          check.setAttribute('disabled', true)
        })
        document.querySelectorAll('.extension-check.hasOwnerPhone').forEach(check => {
          check.removeAttribute('disabled')
          check.checked = true
          check.style.display = 'inline-block'
          check.parentElement.style.display = 'inline-flex'
        })
      } else {
        extensionsChecks.forEach(check => {
          check.style.display = 'inline-flex'
          check.parentElement.style.display = 'inline-flex'
          check.removeAttribute('disabled')
        })
      }
      updateCount()
    })
  }

  if (extensionsFilter) {
    extensionsFilter.addEventListener('input', function(e) {
      let search = e.target.value.trim().toLowerCase()
      if ((search == '') && !ownersOnlyCheckbox.checked) {
        extensionsChecks.forEach(check => {
          check.style.display = 'inline-flex'
          check.parentElement.style.display = 'inline-flex'
          check.removeAttribute('disabled')
        })
        return
      }

      if ((search == '') && ownersOnlyCheckbox.checked) {
        document.querySelectorAll('.extension-check.hasOwnerPhone').forEach(check => {
          check.removeAttribute('disabled')
          check.checked = true
          check.style.display = 'inline-block'
          check.parentElement.style.display = 'inline-flex'
        })
        return
      }

      if (!ownersOnlyCheckbox.checked) {
        extensionsChecks.forEach(check => {
          console.log(search)
          console.log(check.getAttribute('data-apto'))
          check.style.display = check.getAttribute('data-apto').includes(search) ? 'inline-flex' : 'none'
          check.parentElement.style.display = check.getAttribute('data-apto').includes(search) ? 'inline-flex' : 'none'
        })
        return
      }

      document.querySelectorAll('.extension-check.hasOwnerPhone').forEach(check => {
        check.style.display = check.getAttribute('data-apto').includes(search) ? 'inline-flex' : 'none'
        check.parentElement.style.display = check.getAttribute('data-apto').includes(search) ? 'inline-flex' : 'none'
      })

    })
  }

  if (attachmentInput) {
    attachmentInput.addEventListener('change', function(e) {
      let validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf'];

      if (e.target.files && e.target.files.length) {
        if (!validTypes.includes(e.target.files[0].type)) {
          alert('Solo se admiten adjuntos JPG, JPEG, PNG, GIF Y PDF');
          e.target.files[0].value = ''
          return
        }
        document.querySelector('.attachmentDetails').innerHTML = e.target.files[0].name
      }
    })
  }

  function updateCount() {
    let checked = Array.from(extensionsChecks).filter(check => {
      return (check.disabled == false) && (check.checked == true)
    })
    document.querySelector('#receivers-count').innerHTML = checked.length
  }

  function submitMessage() {
    if (!whatsappForm.reportValidity()) {
      return
    }
    if (!window.confirm('Seguro que desea enviar el mensaje?')) {
      return
    }
    whatsappForm.submit()
  }
</script>
@endsection