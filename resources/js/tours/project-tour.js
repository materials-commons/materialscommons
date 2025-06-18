const projectTour = {
    name: 'project',
    steps: [
        {
            id: 'project-intro',
            title: 'Welcome to the Project',
            text: `
<div class="tour-step-content">
    <h3><i class="fas fa-project-diagram"></i> Project Dashboard</h3>
    <div class="tour-description">
        <p>Welcome to your project's command center! 🚀</p>
        <p>This is where all the magic happens - you'll find:</p>
        <ul class="tour-features-list">
            <li><i class="fas fa-info-circle"></i> Project information</li>
            <li><i class="fas fa-cogs"></i> Features and tools</li>
            <li><i class="fas fa-tasks"></i> Project management</li>
        </ul>
    </div>
    <div class="tour-tip">
        <i class="fas fa-lightbulb"></i>
        <span>Pro tip: Mark this as an active project if you'll be accessing this project often.</span>
    </div>
</div>`,
            attachTo: {
                element: '#project-intro',
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
            id: 'project-search-bar',
            title: 'The Search Bar',
            text: `
<div class="tour-step-content">
    <h3><i class="fas fa-search"></i> Project Search</h3>
    <div class="tour-description">
        <p>Find anything in seconds with our powerful search! 🔍</p>
        <div class="search-features">
            <p>Quickly locate:</p>
            <ul class="feature-list">
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
</div>`,
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
            id: 'project-home-tab',
            title: 'Project Home Tab',
            text: `
<div class="tour-step-content">
    <h3><i class="fas fa-home"></i> Home Tab Overview</h3>
    <div class="tour-description">
        <p>Your project's central hub with everything you need! 🏠</p>
        
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-folder-open"></i>
                <h4>Quick Access</h4>
                <p>Jump to any project page instantly</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-book"></i>
                <h4>Documentation</h4>
                <p>Learn about key concepts</p>
            </div>
        </div>

        <div class="tour-navigation-hint">
            <i class="fas fa-compass"></i>
            <span>Always return here if you need to reorient yourself in the project</span>
        </div>
    </div>
</div>`,
            attachTo: {
                element: '#project-home-tab',
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
            id: 'project-overview-tab',
            title: 'Projects Overview Tab',
            text: `
<div class="tour-step-content">
    <h3><i class="fas fa-chart-pie"></i> Project Overview</h3>
    <div class="tour-description">
        <p>Your project at a glance! 📊</p>
        
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-file"></i>
                <span class="stat-label">Files</span>
                <span class="stat-detail">Total count & types</span>
            </div>
            <div class="stat-card">
                <i class="fas fa-flask"></i>
                <span class="stat-label">Samples</span>
                <span class="stat-detail">Sample tracking</span>
            </div>
            <div class="stat-card">
                <i class="fas fa-calculator"></i>
                <span class="stat-label">Computations</span>
                <span class="stat-detail">Process metrics</span>
            </div>
            <div class="stat-card">
                <i class="fas fa-chart-bar"></i>
                <span class="stat-label">Analytics</span>
                <span class="stat-detail">File type breakdown</span>
            </div>
        </div>

        <div class="tour-tip">
            <i class="fas fa-lightbulb"></i>
            <span>Perfect for progress tracking and project health monitoring!</span>
        </div>
    </div>
</div>`,
            attachTo: {
                element: '#project-overview-tab',
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
            id: 'project-sample-attributes-tab',
            title: 'Sample Attributes Tab',
            text: `
<div class="tour-step-content">
    <h3><i class="fas fa-table"></i> Sample Attributes Dictionary</h3>
    <div class="tour-description">
        <p>Your guide to understanding sample data! 📋</p>
        
        <div class="feature-box">
            <div class="feature-content">
                <i class="fas fa-info-circle"></i>
                <div class="feature-text">
                    <p>Sample attributes are automatically loaded when:</p>
                    <ul>
                        <li>A new study is created</li>
                        <li>A spreadsheet is associated with the study</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="tour-resource">
            <i class="fas fa-book"></i>
            <div class="resource-content">
                <span>Need help with spreadsheets?</span>
                <a href="/mcdocs2/guides/spreadsheets.html" 
                   target="_blank" 
                   class="doc-link">
                    View Spreadsheet Documentation
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
        </div>
    </div>
</div>
            `,
            attachTo: {
                element: '#project-sample-attributes-tab',
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
            id: 'project-process-attributes-tab',
            title: 'Process Attributes Tab',
            text: `
<div class="tour-step-content">
    <h3><i class="fas fa-table"></i> Process Attributes Dictionary</h3>
    <div class="tour-description">
        <p>Your guide to understanding process data! 📋</p>
        
        <div class="feature-box">
            <div class="feature-content">
                <i class="fas fa-info-circle"></i>
                <div class="feature-text">
                    <p>Process attributes are automatically loaded when:</p>
                    <ul>
                        <li>A new study is created</li>
                        <li>A spreadsheet is associated with the study</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="tour-resource">
            <i class="fas fa-book"></i>
            <div class="resource-content">
                <span>Need help with spreadsheets?</span>
                <a href="/mcdocs2/guides/spreadsheets.html" 
                   target="_blank" 
                   class="doc-link">
                    View Spreadsheet Documentation
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
        </div>
    </div>
</div>
            `,
            attachTo: {
                element: '#project-process-attributes-tab',
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
            id: 'project-sidebar',
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
                    <span>Studies</span>
                </div>
                <div class="mock-nav-item">
                    <i class="fas fa-cubes"></i>
                    <span>Samples</span>
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
                element: '#project-sidebar',
                on: 'right'
            },
            buttons: [
                {
                    text: 'Next',
                    action: function () {
                        return this.next();
                    }
                }
            ]
        }
    ]
};

export default projectTour;