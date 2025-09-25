// Code.gs
// Initialize properties on first installation
function onInstall(e) {
    const scriptProperties = PropertiesService.getScriptProperties();
    scriptProperties.setProperties({
        'MC_EXPERIMENT_ID': '',
        'MC_PROJECT_ID': '',
        'MC_API_ENDPOINT': 'https://materialscommons.org/api/google-sheets/reload-experiment',
        'MC_SHEET_VERSION': '1.0'
    });
}

// Helper function that ensures user properties exist
function getUserProperty(key, defaultValue = '') {
    const userProperties = PropertiesService.getUserProperties();
    let value = userProperties.getProperty(key);

    if (value === null) {
        // First time this user is accessing this property
        userProperties.setProperty(key, defaultValue);
        value = defaultValue;
    }

    return value;
}

function setUserProperty(key, value) {
    const userProperties = PropertiesService.getUserProperties();
    userProperties.setProperty(key, value);
}


// Create custom menu when sheet opens
function onOpen() {
    SpreadsheetApp.getUi()
        .createMenu('MaterialsCommons')
        .addItem('Send to MaterialsCommons', 'sendToMaterialsCommons')
        .addSeparator()
        .addItem('Configure Settings', 'showConfigDialog')
        .addItem('Select Project & Experiment', 'showProjectExperimentSelector')
        .addSeparator()
        .addItem('Clear My Token', 'clearUserToken')
        .addToUi();
}

// Main function to send data
function sendToMaterialsCommons() {
    const userProperties = PropertiesService.getUserProperties();
    const scriptProperties = PropertiesService.getScriptProperties();

    const apiToken = userProperties.getProperty('MC_API_TOKEN');
    const experimentID = scriptProperties.getProperty('MC_EXPERIMENT_ID');
    const projectID = scriptProperties.getProperty('MC_PROJECT_ID');

    if (!apiToken || !experimentID) {
        SpreadsheetApp.getUi().alert(
            'Configuration Required',
            'Please configure your MaterialsCommons API token and Experiment Id first.',
            SpreadsheetApp.getUi().ButtonSet.OK
        );
        return;
    }

    const apiEndpoint = scriptProperties.getProperty('MC_API_ENDPOINT');

    const spreadsheet = SpreadsheetApp.getActiveSpreadsheet();
    const googleSheetID = spreadsheet.getId();

    const apiUrl = apiEndpoint + "/google-sheets/reload-experiment";

    try {
        const options = {
            'method': 'post',
            'contentType': 'application/json',
            'headers': {
                'Authorization': 'Bearer ' + apiToken
            },
            'payload': JSON.stringify({
                'google_sheet_id': googleSheetID,
                'experiment_id': experimentID,
                'project_id': projectID,
            })
        };

        const response = UrlFetchApp.fetch(apiUrl, options);
        const result = JSON.parse(response.getContentText());

        if (result.success) {
            SpreadsheetApp.getUi().alert('Success', 'Data sent to MaterialsCommons', SpreadsheetApp.getUi().ButtonSet.OK);
        } else {
            throw new Error(result.message || 'Unknown error');
        }
    } catch (error) {
        SpreadsheetApp.getUi().alert('Error', 'Failed to send data: ' + error.toString(), SpreadsheetApp.getUi().ButtonSet.OK);
    }
}

function getUserProjects() {
    const userProperties = PropertiesService.getUserProperties();
    const scriptProperties = PropertiesService.getScriptProperties();
    const apiToken = userProperties.getProperty('MC_API_TOKEN');
    const apiEndpoint = scriptProperties.getProperty('MC_API_ENDPOINT');
    if (!apiToken) {
        SpreadsheetApp.getUi().alert(
            'Configuration Required',
            'Please configure your MaterialsCommons API token.',
            SpreadsheetApp.getUi().ButtonSet.OK
        );
        return;
    }

    try {
        const apiUrl = apiEndpoint + "/projects";
        const options = {
            'method': 'get',
            'contentType': 'application/json',
            'headers': {
                'Authorization': 'Bearer ' + apiToken
            }
        };

        const response = UrlFetchApp.fetch(apiUrl, options);
        const result = JSON.parse(response.getContentText());
        return result.data;
    } catch (error) {
        console.error('Error fetching user projects:', error);
        return [];
    }
}

function getStudiesForProject(projectId) {
    const userProperties = PropertiesService.getUserProperties();
    const scriptProperties = PropertiesService.getScriptProperties();
    const apiToken = userProperties.getProperty('MC_API_TOKEN');
    const apiEndpoint = scriptProperties.getProperty('MC_API_ENDPOINT');
    if (!apiToken) {
        SpreadsheetApp.getUi().alert(
            'Configuration Required',
            'Please configure your MaterialsCommons API token.',
            SpreadsheetApp.getUi().ButtonSet.OK
        );
        return;
    }
    try {
        const apiUrl = apiEndpoint + `/projects/${projectId}/experiments`;
        const options = {
            'method': 'get',
            'contentType': 'application/json',
            'headers': {
                'Authorization': 'Bearer ' + apiToken
            }
        };

        const response = UrlFetchApp.fetch(apiUrl, options);
        const result = JSON.parse(response.getContentText());
        return result.data;
    } catch (error) {
        console.error('Error fetching user projects:', error);
        return [];
    }

}

// Configuration dialog HTML
function showConfigDialog() {
    const config = getConfig();
    const html = HtmlService.createTemplateFromFile('set-api-key');
    html.config = config;
    const htmlOutput = html.evaluate().setWidth(600).setHeight(500);
    // const html = HtmlService.createHtmlOutputFromFile('ConfigDialog')
    //     .setWidth(600)
    //     .setHeight(500);
    SpreadsheetApp.getUi().showModalDialog(htmlOutput, 'MaterialsCommons Configuration');
}

function showProjectExperimentSelector() {
    const config = getConfig();
    const projects = getUserProjects();

    const html = HtmlService.createTemplateFromFile('project-experiment-selector');
    html.projects = projects;
    html.selectedProjectId = config.MC_PROJECT_ID || '';

    const htmlOutput = html.evaluate().setWidth(500).setHeight(400);
    SpreadsheetApp.getUi().showModalDialog(htmlOutput, 'Select Project and Experiment');
}


// Save configuration with user-specific tokens
function saveConfig(config) {
    initializeUserProperties();

    const userProperties = PropertiesService.getUserProperties();
    const scriptProperties = PropertiesService.getScriptProperties();

    // Store sensitive data per-user
    if (config.MC_API_TOKEN !== undefined) {
        userProperties.setProperty('MC_API_TOKEN', config.MC_API_TOKEN);
    }

    // Store non-sensitive shared data in script properties
    const sharedConfig = {};
    if (config.MC_API_ENDPOINT) {
        sharedConfig.MC_API_ENDPOINT = config.MC_API_ENDPOINT;
    }

    if (config.MC_EXPERIMENT_ID) {
        sharedConfig.MC_EXPERIMENT_ID = config.MC_EXPERIMENT_ID;
    }

    if (config.MC_PROJECT_ID) {
        sharedConfig.MC_PROJECT_ID = config.MC_PROJECT_ID;
    }

    if (config.SHEET_VERSION) {
        sharedConfig.SHEET_VERSION = config.SHEET_VERSION;
    }

    if (Object.keys(sharedConfig).length > 0) {
        scriptProperties.setProperties(sharedConfig);
    }

    return true;
}

function saveProjectStudySelection(project, experiment) {
    const scriptProperties = PropertiesService.getScriptProperties();

    scriptProperties.setProperties({
        'MC_PROJECT_ID': project.id,
        'MC_PROJECT_NAME': project.name,
        'MC_EXPERIMENT_ID': experiment.id,
        'MC_EXPERIMENT_NAME': experiment.name
    });

    return true;
}

// Get current configuration (mixed from user and script properties)
function getConfig() {
    initializeUserProperties();
    const userProperties = PropertiesService.getUserProperties();
    const scriptProperties = PropertiesService.getScriptProperties();

    const config = {};

    // Get user-specific properties
    config.MC_API_TOKEN = userProperties.getProperty('MC_API_TOKEN') || '';

    // Get shared properties
    const sharedProps = scriptProperties.getProperties();
    Object.assign(config, sharedProps);

    return config;
}

// Initialize user properties when needed
function initializeUserProperties() {
    const userProperties = PropertiesService.getUserProperties();

    // Check if user properties are already initialized
    if (!userProperties.getProperty('INITIALIZED')) {
        userProperties.setProperties({
            'MC_API_TOKEN': '',
            'INITIALIZED': 'true'
        });
    }
}

// Clear user's stored token (for security/logout)
function clearUserToken() {
    const userProperties = PropertiesService.getUserProperties();
    userProperties.deleteProperty('MC_API_TOKEN');
    SpreadsheetApp.getUi().alert('Token cleared', 'Your API token has been removed.', SpreadsheetApp.getUi().ButtonSet.OK);
}
