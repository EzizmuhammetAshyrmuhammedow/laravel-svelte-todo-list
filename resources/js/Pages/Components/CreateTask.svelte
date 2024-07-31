<script>
    import { createEventDispatcher } from "svelte";

    let taskName = "";
    let dueDate = "";
    let priority = "medium";
    let status = "pending";
    let isOpen = false;

    const dispatch = createEventDispatcher();
    let errors = {};

    function validateForm() {
        errors = {};
        if (!taskName) {
            errors.taskName = "Task name is required";
        }
        if (!dueDate) {
            errors.dueDate = "Due date is required";
        }
        if (!priority) {
            errors.priority = "Priority is required";
        }
        if (!status) {
            errors.status = "Status is required";
        }
        return Object.keys(errors).length === 0;
    }

    async function createTask() {
        if (!validateForm()) {
            return;
        }

        try {
            const response = await fetch("/tasks", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
                body: JSON.stringify({
                    task_name: taskName,
                    due_date: dueDate,
                    priority,
                    status,
                }),
            });

            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            window.location.reload()

            const data = await response.json();
            console.log("Task created:", data.task);

            // Dispatch event with new task
            dispatch("taskCreated", data.task);

            // Reset form fields
            taskName = "";
            dueDate = "";
            priority = "medium";
            status = "pending";
        } catch (error) {
            console.error("Error creating task:", error);
        }
    }

    function closeModal() {
        isOpen = false;
    }

    function createTaskCloseModal() {
        if (!validateForm()) {
            return;
        } else {
        createTask();
        closeModal();
        }
    }
</script>

<button class="button is-primary" on:click={() => (isOpen = true)}>Create a Task</button>

<div id="task-modal" class="modal" class:is-active={isOpen}>
    <div class="modal-background" on:click={closeModal}></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Create a Task</p>
        </header>
        <section class="modal-card-body">
            <form on:submit|preventDefault={createTask} class="box">
                <div class="field">
                    <label class="label">Task Name</label>
                    <div class="control">
                        <input bind:value={taskName} placeholder="Task name" required class="input" />
                    </div>
                    {#if errors.taskName}
                        <p class="help is-danger">{errors.taskName}</p>
                    {/if}
                </div>

                <div class="field">
                    <label class="label">Due Date</label>
                    <div class="control">
                        <input type="date" bind:value={dueDate} required class="input" />
                    </div>
                    {#if errors.dueDate}
                        <p class="help is-danger">{errors.dueDate}</p>
                    {/if}
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
                    {#if errors.priority}
                        <p class="help is-danger">{errors.priority}</p>
                    {/if}
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
                    {#if errors.status}
                        <p class="help is-danger">{errors.status}</p>
                    {/if}
                </div>
            </form>
        </section>
        <footer class="modal-card-foot">
            <button class="button is-primary" on:click={createTaskCloseModal}>Create</button>
            <button class="button" on:click={closeModal}>Cancel</button>
        </footer>
    </div>
</div>

<style>
    .modal-card-head {
        z-index: 100;
    }

    .help.is-danger {
        color: red;
    }
</style>
