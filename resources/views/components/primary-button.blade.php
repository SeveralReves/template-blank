<button {{ $attributes->merge(['type' => 'submit', 'class' => 'button__primary']) }}>
    {{ $slot }}
</button>
