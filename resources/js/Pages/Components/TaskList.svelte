<script>
    import { onMount } from "svelte";
    import CreateTask from "./CreateTask.svelte";
    import { createEventDispatcher } from "svelte";
    import { page } from "@inertiajs/svelte";

    export let searchQuery = '';

    let tasks = [];
    let isDeleteOpen = false;
    let isUpdateOpen = false;
    let currentTask = null;
    let taskName = "";
    let dueDate = "";
    let priority = "medium";
    let status = "pending";
    let sortKey = '';
    let sortOrder = 1; // 1 for ascending, -1 for descending

    const dispatch = createEventDispatcher();

    onMount(async () => {
        await fetchTasks();
    });

    async function fetchTasks() {
        try {
            const response = await fetch("/tasks");
            if (response.ok) {
                tasks = await response.json();
            } else {
                console.error("Failed to fetch tasks");
            }
        } catch (error) {
            console.error("Error fetching tasks:", error);
        }
    }

    function handleTaskCreated(newTask) {
        fetchTasks();
        tasks = [...tasks, newTask]; // Update tasks array with new task
    }

    async function deleteTask(taskId) {
        try {
            const response = await fetch(`/tasks/${taskId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
            });

            if (!response.ok) {
                throw new Error("Network response was not ok");
            }

            await response.json();
            console.log("Task deleted successfully");

            // Remove the task from the local array
            tasks = tasks.filter((task) => task.id !== taskId);
        } catch (error) {
            console.error("Error deleting task:", error);
            alert("Failed to delete task. Please try again.");
        }
    }

    async function updateTask() {
        if (!currentTask) return; // Ensure a task is selected

        try {
            const response = await fetch(`/tasks/${currentTask.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    task_name: taskName,
                    due_date: dueDate,
                    priority,
                    status
                })
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            console.log('Task updated successfully:', data);

            // Update the task in the local state
            const index = tasks.findIndex(task => task.id === currentTask.id);
            if (index !== -1) {
                tasks[index] = data.task;
            }

        } catch (error) {
            console.error('Error updating task:', error);
            // Handle error, e.g., show an error message to the user
        }
    }

    function openUpdateModal(taskId) {
        currentTask = tasks.find((task) => task.id === taskId); // Find the task by ID
        if (currentTask) {
            taskName = currentTask.task_name;
            dueDate = currentTask.due_date;
            priority = currentTask.priority;
            status = currentTask.status;
        }
        isUpdateOpen = true;
    }

    function openDeleteModal(taskId) {
        currentTask = taskId;
        isDeleteOpen = true;
    }

    function closeUpdateModal() {
        isUpdateOpen = false;
        currentTask = null; // Clear the task object
        // Reset form fields to avoid data persistence
        taskName = "";
        dueDate = "";
        priority = "medium";
        status = "pending";
    }

    function closeDeleteModal() {
        isDeleteOpen = false;
        currentTask = null; // Clear the task ID
    }

    function updateTaskCloseModal(){
        updateTask();
        fetchTasks();
        closeUpdateModal();
    }

    function deleteTaskCloseModal() {
        deleteTask(currentTask);
        closeDeleteModal();
    }

    function sortTasks(key) {
        if (sortKey === key) {
            sortOrder *= -1; // Toggle sort order
        } else {
            sortKey = key;
            sortOrder = 1; // Default to ascending order
        }
        tasks = tasks.slice().sort((a, b) => {
            if (a[key] < b[key]) return -1 * sortOrder;
            if (a[key] > b[key]) return 1 * sortOrder;
            return 0;
        });
    }

    function convertStatus(status) {
        return status === 'in_progress' ? 'In Progress' : status;
    }

    $: filteredTasks = tasks.filter(task => task.task_name.toLowerCase().includes(searchQuery.toLowerCase()));
    $: pendingTasks = filteredTasks.filter(task => task.status !== 'completed');
    $: completedTasks = filteredTasks.filter(task => task.status === 'completed');
</script>

<style>
    .completed {
        text-decoration: line-through;
    }
   .create-modal-button{
       position: fixed;
       right: 0;
       bottom: 0;
       padding: 10px;
    }
    th{
        cursor: pointer;
    }
</style>

<div class="mt-5">
    {#if pendingTasks.length > 0}
        <h2>Tasks</h2>
        <table class="table is-dark is-fullwidth">
            <thead>
                <tr>
                    <th on:click={() => sortTasks('task_name')}>
                        Task Name
                        {#if sortKey === 'task_name'}
                            <span class="icon">{sortOrder === 1 ? '⬇️' : '⬆️'}</span>
                        {/if}
                    </th>
                    <th on:click={() => sortTasks('priority')}>
                        Priority
                        {#if sortKey === 'priority'}
                            <span class="icon">{sortOrder === 1 ? '⬇️' : '⬆️'}</span>
                        {/if}
                    </th>
                    <th on:click={() => sortTasks('due_date')}>
                        Due Date
                        {#if sortKey === 'due_date'}
                            <span class="icon">{sortOrder === 1 ? '⬇️' : '⬆️'}</span>
                        {/if}
                    </th>
                    <th on:click={() => sortTasks('status')}>
                        Status
                        {#if sortKey === 'status'}
                            <span class="icon">{sortOrder === 1 ? '⬇️' : '⬆️'}</span>
                        {/if}
                    </th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                {#each pendingTasks as task}
                    <tr>
                        <td>{task.task_name}</td>
                        <td>{task.priority}</td>
                        <td>{task.due_date}</td>
                        <td>{convertStatus(task.status)}</td>
                        <td><button class="button is-warning" on:click={() => openUpdateModal(task.id)}>Edit</button></td>
                        <td>
                            <button class="button is-danger" on:click={() => openDeleteModal(task.id)}>Delete</button>
                        </td>
                    </tr>
                {/each}
            </tbody>
        </table>
    {:else}
        <p>No pending or in progress tasks found.</p>
    {/if}

    {#if completedTasks.length > 0}
        <h2>Completed Tasks</h2>
        <table class="table is-dark is-fullwidth">
            <thead>
                <tr>
                    <th on:click={() => sortTasks('task_name')}>
                        Task Name
                        {#if sortKey === 'task_name'}
                            <span class="icon">{sortOrder === 1 ? '⬇️' : '⬆️'}</span>
                        {/if}
                    </th>
                    <th on:click={() => sortTasks('priority')}>
                        Priority
                        {#if sortKey === 'priority'}
                            <span class="icon">{sortOrder === 1 ? '⬇️' : '⬆️'}</span>
                        {/if}
                    </th>
                    <th on:click={() => sortTasks('due_date')}>
                        Due Date
                        {#if sortKey === 'due_date'}
                            <span class="icon">{sortOrder === 1 ? '⬇️' : '⬆️'}</span>
                        {/if}
                    </th>
                    <th on:click={() => sortTasks('status')}>
                        Status
                        {#if sortKey === 'status'}
                            <span class="icon">{sortOrder === 1 ? '⬇️' : '⬆️'}</span>
                        {/if}
                    </th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                {#each completedTasks as task}
                    <tr class="completed">
                        <td>{task.task_name}</td>
                        <td>{task.priority}</td>
                        <td>{task.due_date}</td>
                        <td>{convertStatus(task.status)}</td>
                        <td><button class="button is-warning" on:click={() => openUpdateModal(task.id)}>Edit</button></td>
                        <td>
                            <button class="button is-danger" on:click={() => openDeleteModal(task.id)}>Delete</button>
                        </td>
                    </tr>
                {/each}
            </tbody>
        </table>
    {:else}
        <p>No completed tasks found.</p>
    {/if}
    <div class="create-modal-button">
        <CreateTask on:taskCreated={handleTaskCreated} />
    </div>
</div>

{#if isDeleteOpen}
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Confirm Delete</p>
                <button class="delete" aria-label="close" on:click={closeDeleteModal}></button>
            </header>
            <section class="modal-card-body">
                <p>Are you sure you want to delete this task?</p>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-danger" on:click={deleteTaskCloseModal}>Delete</button>
                <button class="button" on:click={closeDeleteModal}>Cancel</button>
            </footer>
        </div>
    </div>
{/if}

{#if isUpdateOpen}
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Update Task</p>
                <button class="delete" aria-label="close" on:click={closeUpdateModal}></button>
            </header>
            <section class="modal-card-body">
                <form>
                    <div class="field">
                        <label class="label">Task Name</label>
                        <div class="control">
                            <input class="input" type="text" bind:value={taskName} />
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Due Date</label>
                        <div class="control">
                            <input class="input" type="date" bind:value={dueDate} />
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Priority</label>
                        <div class="control">
                            <div class="select">
                                <select bind:value={priority}>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Status</label>
                        <div class="control">
                            <div class="select">
                                <select bind:value={status}>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success" on:click={updateTaskCloseModal}>Save changes</button>
                <button class="button" on:click={closeUpdateModal}>Cancel</button>
            </footer>
        </div>
    </div>
{/if}


