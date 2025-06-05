<li class="nav-item">
  <a class="nav-link" href="{{ route('admin.users.index') }}">SuperUsuarios</a>
</li>
<li class="nav-item">
  <a class="nav-link" href="{{ route('admin.admins.index') }}">Administradores</a>
</li>
<li class="nav-item">
  <a class="nav-link" href="{{ route('admin.porterias.index') }}">Porterias</a>
</li>
<li class="nav-item">
  <a class="nav-link" href="{{ route('admin.invoices.upload') }}">Facturas</a>
</li>
<li class="nav-item dropdown">
  <a
    href="#"
    class="nav-link dropdown-toggle"
    data-bs-toggle="dropdown">
    Whatsapp
  </a>
  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
    <li>
      <a class="dropdown-item" href="{{ route('admin.batch_messages.index') }}">Mensajes masívos</a>
    </li>
    <li>
      <a class="dropdown-item" href="{{ route('admin.whatsapp_instances.index') }}">Instancias</a>
    </li>
    <li>
      <a class="dropdown-item" href="{{ route('admin.whatsapp_clients.index') }}">Proveedores</a>
    </li>
  </ul>
</li>