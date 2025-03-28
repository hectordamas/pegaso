@forelse($chats as $chat)
<div class="chat-message d-flex mb-3 
    @if(auth()->user()->id === $chat->user->id) 
        chat-my-message
    @else 
        chat-other-message
    @endif
    ">
    <!-- Foto de perfil del usuario -->
    <div class="chat-avatar mr-3">
        <img 
            @if($chat->user->photo)
                src="{{ strlen($chat->user->photo) > 3 ? $chat->user->photo : asset('assets/customAssets/img/user_default.jpg') }}" 
            @else   
                src="{{ asset('assets/customAssets/img/user_default.jpg') }}"
            @endif
            alt="{{ $chat->user->name }}" 
            class="rounded-circle" 
            width="50" height="50"
        >
    </div>

    <!-- Mensaje de chat -->
    <div class="chat-content w-100">
        <div class="chat-header d-flex align-items-center">
            <span class="mr-5"><strong>{{ $chat->user->name }}</strong> @if(auth()->user()->id === $chat->user->id) (Yo) @endif</span>
            <small class="text-muted">{{ \Carbon\Carbon::parse($chat->fechayhora)->format('d/m/Y h:i A') ?? 'Fecha no disponible' }}</small>
        </div>
        
        <p class="chat-text">{{ $chat->mensaje }}</p>
    </div>
</div>
@empty
    <div class="row">
        <div class="col-md-12 py-5 text-center">
            <i class="far fa-comment-alt mb-3" style="font-size: 50px"></i>
            <h5>Aún no hay mensajes disponibles</h5>
            <span>Escribe un nuevo mensaje para inicializar este chat</span>
        </div>
    </div>
@endforelse