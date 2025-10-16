# DNA Design Advanced Workflow Extensions

This module provides additional functionality for the SilverStripe Advanced Workflow module, including urgency system and cancel workflow capabilities.

## Features

### Urgency System
- **Urgency Flag**: Mark workflows as urgent or normal priority
- **Change Level**: Categorize changes as "New page", "Minor edit", "Major edit", or "Re-submission"
- **Priority Sorting**: Urgent workflows appear first in workflow lists
- **Admin Filtering**: Filter workflows by urgency and change level in the admin interface

### Cancel Workflow
- **Cancel Button**: Add cancel workflow buttons to CMS pages
- **Permission Control**: Configure which workflow actions allow cancellation
- **Email Notifications**: Automatic email notifications when workflows are cancelled
- **Confirmation Dialog**: JavaScript confirmation before cancelling workflows

## Requirements

- SilverStripe CMS ^6.0
- symbiote/silverstripe-advancedworkflow ^7.0

## Installation

```bash
composer require dnadesign/silverstripe-advanceder-workflow
```

## Configuration

The module automatically extends the following classes:

- `Symbiote\AdvancedWorkflow\Services\WorkflowService`
- `Symbiote\AdvancedWorkflow\DataObjects\WorkflowInstance`
- `Symbiote\AdvancedWorkflow\DataObjects\WorkflowActionInstance`
- `Symbiote\AdvancedWorkflow\DataObjects\WorkflowAction`
- `Page`
- `SilverStripe\CMS\Controllers\CMSPageEditController`

## Database Changes

The module adds the following database fields:

### WorkflowInstance
- `IsUrgent`: Enum("No,Yes","No")
- `ChangeLevel`: Enum("New page,Minor edit,Major edit,Re-submission","New page")
- `URL`: Varchar(255)

### WorkflowActionInstance
- `IsUrgent`: Enum("No,Yes","No")
- `ChangeLevel`: Enum("New page,Minor edit,Major edit,Re-submission","New page")

### WorkflowAction
- `AllowCancel`: Boolean

## Usage

### Setting Urgency
When starting a workflow, users can mark it as urgent and select the appropriate change level. This information is displayed in workflow lists and can be used for filtering.

### Cancelling Workflows
If a workflow action has "Allow users to cancel this workflow" enabled, users with appropriate permissions can cancel the workflow from the page edit interface.

### Email Templates
The module includes email templates for cancel notifications. You can override these by creating your own templates in your theme:

```
templates/DNADesign/AdvancederWorkflow/Email/CancelEmail.ss
```

## License

BSD-3-Clause

## Maintainer

DNA Design
