<form action="{{ route('vehicles.import') }}" method="POST">
    @csrf
    <input type="hidden" name="client_id" value="1"> <!-- Nurodykite numatytąjį klientą -->
    <button type="submit" class="btn btn-primary">Importuoti automobilius iš API</button>
</form> 