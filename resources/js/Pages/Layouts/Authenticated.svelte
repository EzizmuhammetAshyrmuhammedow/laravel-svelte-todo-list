<script>
    import { Link } from "@inertiajs/svelte";
    import { page } from "@inertiajs/svelte";
    import TaskList from "../Components/TaskList.svelte";
    import LogoutButton from "../Components/LogoutButton.svelte";

    $: isLoggedIn = $page.props.isLoggedIn;
    let searchQuery = '';

    function handleSearch(event) {
        searchQuery = event.target.value;
    }
</script>

<html lang="en" class="theme-dark">
    <body data-bs-theme="dark">
        <nav class="navbar navbar-expand-lg has-background-black-bis">
            <div class="container-fluid">
                <Link
                    href="/"
                    class="link-underline link-underline-opacity-0 mt-2 p-2"
                    ><h3>Todo App</h3></Link
                >
                <div class="container d-flex justify-content-end gap-3">
                    <form class="d-flex" role="search">
                        <input
                            class="input is-medium me-2"
                            type="search"
                            placeholder="Search Task"
                            aria-label="Search"
                            on:input={handleSearch}
                        />
                        <button class="button" type="button">Search</button>
                    </form>
                    <Link href="/settings" class="text-white">
                        <i class="bi bi-gear-fill" style="font-size: 2rem;"></i>
                    </Link>
                </div>
            </div>
        </nav>
        <main data-bs-theme="dark">
            <TaskList {searchQuery} />
        </main>
    </body>
</html>
