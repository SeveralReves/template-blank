<div class="layout__admin--nav">
    <a href="{{ route('dashboard') }}" class="layout__admin--nav-item {{ request()->routeIs('dashboard') ? 'active' : ''}}">
      <svg fill="{{ request()->routeIs('dashboard') ? '#50A7E5' : '#fff'}}" viewBox="0 -7 42 42" width="20" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path id="_48.Dashboard" data-name="48.Dashboard" d="M44.9,32.971c0,.011.006.019.006.029s0,.01,0,.016a.076.076,0,0,1,0,.016,1,1,0,0,1-1,1C43.921,34.031,35,34,35,34a1,1,0,0,1,0-2h7.946c.019-.334.054-.662.054-1A19,19,0,0,0,5,31c0,.338.035.666.053,1H13a1,1,0,0,1,0,2l-8.906.031a1,1,0,0,1-1-1,.076.076,0,0,1,0-.016c0-.006,0-.011,0-.016s.006-.019.006-.028C3.039,32.321,3,31.665,3,31a21,21,0,0,1,42,0C45,31.665,44.961,32.321,44.9,32.971ZM17.523,21.128a1.052,1.052,0,0,1,1.189.133l-.05-.083c8.774,6.42,10.577,9.373,10.61,9.428a4.8,4.8,0,0,1-1.9,6.731,5.339,5.339,0,0,1-7.085-1.8c-.035-.053-1.824-3.014-3.291-13.442l.051.082A.948.948,0,0,1,17.523,21.128Zm4.563,13.421a3.161,3.161,0,0,0,2.7,1.478,3.231,3.231,0,0,0,1.552-.4,2.959,2.959,0,0,0,1.45-1.794,2.8,2.8,0,0,0-.3-2.232c-.017-.025-1.572-2.386-8.062-7.348C20.7,32.071,22.075,34.529,22.086,34.549Z" transform="translate(-3 -10)" fill-rule="evenodd"></path> </g></svg>
      <span>
        Dashboard
      </span>
    </a>
    <a href="{{ route('users') }}" class="layout__admin--nav-item {{ request()->routeIs('users') ? 'active' : ''}}">
      <svg viewBox="0 0 24 24" width="20" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M13 20V18C13 15.2386 10.7614 13 8 13C5.23858 13 3 15.2386 3 18V20H13ZM13 20H21V19C21 16.0545 18.7614 14 16 14C14.5867 14 13.3103 14.6255 12.4009 15.6311M11 7C11 8.65685 9.65685 10 8 10C6.34315 10 5 8.65685 5 7C5 5.34315 6.34315 4 8 4C9.65685 4 11 5.34315 11 7ZM18 9C18 10.1046 17.1046 11 16 11C14.8954 11 14 10.1046 14 9C14 7.89543 14.8954 7 16 7C17.1046 7 18 7.89543 18 9Z" stroke="{{ request()->routeIs('users') ? '#50A7E5' : '#fff'}}" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
      <span>
        Usuarios
      </span>
    </a>
</div>