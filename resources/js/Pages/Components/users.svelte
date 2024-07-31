<script>
    import {Link} from '@inertiajs/svelte'
    export let users = [];
</script>

<Link href="/create-user">New user</Link>

<main>
    <h1>User List</h1>
    <ul>
        {#each users as user}
            <li>{user.name} ({user.email})</li>
        {/each}
    </ul>
</main>
