const dashboardTour = {
    name: 'dashboard',
    steps: [
        {
            id: 'dashboard-intro',
            title: 'Welcome to the Dashboard',
            text: `
<div id="dashboard-intro" class="tour-highlight">
    <h4 class="mb-3">Welcome to Materials Commons</h4>
    <p class="tour-text">
        The dashboard is your central hub for managing all your Materials Commons work. Here you'll find:
        <ul class="tour-list mt-2">
            <li>Quick access to all your accessible projects</li>
            <li>Your published datasets collection</li>
            <li>Active and recently accessed projects</li>
            <li>Tools to organize and manage your research</li>
        </ul>
    </p>
</div>
            `,
            attachTo: {
                element: '#dashboard-intro',
                on: 'bottom'
            },
            buttons: [
                {
                    text: 'Next',
                    action: function () {
                        return this.next();
                    }
                }
            ]
        },
        {
            id: 'dashboard-search-bar',
            title: 'The Search Bar',
            text: `
<div class="tour-step-content">
    <h3><i class="fas fa-search"></i> Search Across Projects</h3>
    <div class="tour-description">
        <p>Find anything in all your projects in seconds with our powerful search! 🔍</p>
        <div class="search-features">
            <p>Quickly locate:</p>
            <ul class="feature-list">
                <li><i class="fas fa-vector-square"></i> Projects</li>
                <li><i class="fas fa-flask"></i> Samples</li>
                <li><i class="fas fa-calculator"></i> Computations</li>
                <li><i class="fas fa-database"></i> Datasets</li>
                <li><i class="fas fa-file-alt"></i> Files</li>
            </ul>
        </div>
    </div>
<!--    <div class="tour-tip">-->
<!--        <i class="fas fa-keyboard"></i>-->
<!--        <span>Pro tip: Press <kbd>/</kbd> to focus the search bar instantly!</span>-->
<!--    </div>-->
</div>
            `,
            attachTo: {
                element: '#navbar-search-input',
                on: 'bottom'
            },
            buttons: [
                {
                    text: 'Next',
                    action: function () {
                        return this.next();
                    }
                }
            ]
        },
        {
            id: 'dashboard-projects',
            title: 'Projects',
            text: `
<div id="dashboard-projects" class="tour-highlight">
    <h4 class="mb-3">Projects Overview</h4>
    <p class="tour-text">
        The projects tab is your research command center, providing:
        <ul class="tour-list mt-2">
            <li>Access to all your available projects</li>
            <li>Keep active projects quickly accessible</li>
            <li>See recently accessed projects</li>
            <li>Quick sorting and filtering options</li>
        </ul>
    </p>
<!--    <p class="tour-tip mt-2">-->
<!--        <i class="fas fa-lightbulb"></i> -->
<!--        <em>Tip: Use the search bar above to quickly find specific projects.</em>-->
<!--    </p>-->
</div>`,
            attachTo: {
                element: '#dashboard-projects-tab',
                on: 'bottom'
            },
            buttons: [
                {
                    text: 'Next',
                    action: function () {
                        return this.next();
                    }
                }
            ]
        },
        {
            id: 'dashboard-published-datasets',
            title: 'Published Datasets',
            text: `
<div id="dashboard-published-datasets" class="tour-highlight">
    <h4 class="mb-3">Published Datasets</h4>
    <p class="tour-text">
        Your research showcase center where you can:
        <ul class="tour-list mt-2">
            <li>Access all your published datasets in one place</li>
            <li>Find datasets quickly without browsing through projects</li>
            <li>Track your research contributions</li>
            <li>Share and reference your published work</li>
        </ul>
    </p>
    <p class="tour-tip mt-2">
        <i class="fas fa-lightbulb"></i> 
        <em>Tip: Use filters and sorting to organize datasets by publication date or project name.</em>
    </p>
</div>
            `,
            attachTo: {
                element: '#dashboard-published-datasets-tab',
                on: 'bottom'
            },
            buttons: [
                {
                    text: 'Next',
                    action: function () {
                        return this.next();
                    }
                }
            ]
        },
        {
            id: 'dashboard-archived-projects',
            title: 'Archived Projects',
            text:`
<div id="dashboard-archived-projects" class="tour-highlight">
    <h4 class="mb-3">Archived Projects</h4>
    <p class="tour-text">
        The archive serves as a organized storage for your completed work:
        <ul class="tour-list mt-2">
            <li>Access all projects marked as no longer active</li>
            <li>Declutter your main projects view</li>
            <li>Maintain full access to project contents</li>
            <li>Restore projects back to active status anytime</li>
        </ul>
    </p>
    <div class="tour-note mt-2">
        <i class="fas fa-info-circle"></i>
        <span>Archived projects remain fully accessible but won't appear in your projects tab.</span>
    </div>
    <p class="tour-tip mt-2">
        <i class="fas fa-lightbulb"></i> 
        <em>Tip: Archive completed projects to keep your active workspace focused and efficient.</em>
    </p>
</div>`,
            attachTo: {
                element: '#dashboard-archived-projects-tab',
                on: 'bottom'
            },
            buttons: [
                {
                    text: 'Next',
                    action: function () {
                        return this.next();
                    }
                }
            ]
        },
        {
            id: 'dashboard-projects-trash',
            title: 'Deleted Projects',
            text: `
<div id="dashboard-projects-trash" class="tour-highlight">
    <h4 class="mb-3">Project Trash Bin</h4>
    <p class="tour-text">
        The trash bin provides a safety net for deleted projects:
        <ul class="tour-list mt-2">
            <li>Access recently deleted projects</li>
            <li>Restore projects with all their data intact</li>
            <li>Review projects before permanent deletion</li>
            <li>Manage deleted content efficiently</li>
        </ul>
    </p>
    <div class="tour-warning mt-2">
        <i class="fas fa-clock"></i>
        <span>Projects in trash are automatically removed after <strong>7 days</strong></span>
    </div>
    <p class="tour-tip mt-2">
        <i class="fas fa-lightbulb"></i> 
        <em>Tip: Check the deletion date to know when projects will be permanently removed.</em>
    </p>
</div>
            `,
            attachTo: {
                element: '#dashboard-projects-trash-tab',
                on: 'bottom'
            },
            buttons: [
                {
                    text: 'Next',
                    action: function () {
                        return this.next();
                    }
                }
            ]
        },
        {
            id: 'dashboard-active-projects',
            title: 'Active Projects',
            text: `
<div id="dashboard-active-projects" class="tour-highlight">
    <h4 class="mb-3">Active Projects</h4>
    <p class="tour-text">
        Keep your most important work front and center:
        <ul class="tour-list mt-2">
            <li>Prioritize frequently accessed projects</li>
            <li>Create shortcuts to current work</li>
            <li>Customize your project dashboard</li>
            <li>Toggle active status instantly</li>
        </ul>
    </p>
    <div class="tour-action mt-2">
        <i class="fas fa-check-circle"></i>
        <span>To mark a project as active, click the <strong>checkmark icon</strong> <i class="fa fa-check"></i> next to its name</span>
    </div>
    <p class="tour-tip mt-2">
        <i class="fas fa-lightbulb"></i> 
        <em>Tip: Use active projects to create a quick-access list of your current research focus.</em>
    </p>
</div>
            `,
            attachTo: {
                element: '#dashboard-active-projects',
                on: 'bottom'
            },
            buttons: [
                {
                    text: 'Next',
                    action: function () {
                        return this.next();
                    }
                }
            ]
        },
        {
            id: 'dashboard-recent-projects',
            title: 'Recently Accessed Projects',
            text: `
<div id="dashboard-recent-projects" class="tour-highlight">
    <h4 class="mb-3">Recently Accessed Projects</h4>
    <p class="tour-text">
        Your project activity timeline provides:
        <ul class="tour-list mt-2">
            <li>Automatic tracking of visited projects</li>
            <li>Quick access to your last 2 weeks of work</li>
            <li>Smart filtering (excludes Active Projects)</li>
            <li>History-based project navigation</li>
        </ul>
    </p>
    <div class="tour-info mt-2">
        <i class="fas fa-history"></i>
        <span>Projects that aren't marked as active, that you've accessed within the <strong>past 14 days</strong> appear here automatically.</span>
    </div>
    <p class="tour-tip mt-2">
        <i class="fas fa-lightbulb"></i> 
        <em>Tip: Use this list to resume work on recent projects or mark frequently visited ones as Active.</em>
    </p>
</div>`,
            attachTo: {
                element: '#dashboard-recent-projects',
                on: 'bottom'
            },
            buttons: [
                {
                    text: 'Next',
                    action: function () {
                        return this.next();
                    }
                }
            ]
        },
        {
            id: 'dashboard-all-projects',
            title: 'All Projects',
            text: `
<div id="dashboard-projects-table" class="tour-highlight">
    <h4 class="mb-3">Projects Overview</h4>
    <p class="tour-text">
        Your central hub for project management:
        <ul class="tour-list mt-2">
            <li>View all your accessible, non-archived projects</li>
            <li>Sort and filter projects efficiently</li>
            <li>Access project details with a single click</li>
            <li>Monitor project status at a glance</li>
        </ul>
    </p>
    <div class="tour-interaction mt-2">
        <i class="fas fa-mouse-pointer"></i>
        <span>Click any <strong>project name</strong> to open its detailed view</span>
    </div>
    <div class="tour-note mt-2">
        <i class="fas fa-info-circle"></i>
        <span>Projects remain here until explicitly moved to the Archive</span>
    </div>
    <p class="tour-tip mt-2">
        <i class="fas fa-lightbulb"></i> 
        <em>Tip: Use column headers to sort projects by different criteria.</em>
    </p>
</div>`,
            attachTo: {
                element: '#projects',
                on: 'bottom'
            },
            buttons: [
                {
                    text: 'Next',
                    action: function () {
                        return this.next();
                    }
                }
            ]
        },
        {
            id: 'dashboard-sidebar',
            title: 'Sidebar Navigation',
            text: `
<div class="tour-step-content">
    <h3><i class="fas fa-compass"></i> Navigation Sidebar</h3>
    <div class="tour-description">
        <p>Your project navigation command center! 🗺️</p>
        
        <div class="nav-demo">
            <div class="sidebar-preview">
                <div class="mock-nav-item active">
                    <i class="fas fa-vector-square"></i>
                    <span>Dashboard</span>
                </div>
                <div class="mock-nav-item">
                    <i class="fas fa-flask"></i>
                    <span>Public Data</span>
                </div>
                <div class="mock-nav-item">
                    <i class="fas fa-cubes"></i>
                    <span>Account</span>
                </div>
            </div>
        </div>

        <div class="features-list">
            <div class="feature-item">
                <i class="fas fa-thumbtack"></i>
                <span>Always visible on the left</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-mouse-pointer"></i>
                <span>One-click navigation</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-location-arrow"></i>
                <span>Quick access to all pages</span>
            </div>
        </div>

<!--        <div class="tour-tip">-->
<!--            <i class="fas fa-keyboard"></i>-->
<!--            <span>Pro tip: Use <kbd>Alt</kbd> + <kbd>←</kbd> to return to previous page</span>-->
<!--        </div>-->
    </div>
</div>         
            `,
            attachTo: {
                element: '#dashboard-sidebar',
                on: 'right'
            },
            buttons: [
                {
                    text: 'Done',
                    action: function () {
                        return this.next();
                    }
                }
            ]
        }
    ]
}

export default dashboardTour;