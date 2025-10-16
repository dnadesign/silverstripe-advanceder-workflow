<?php

namespace DNADesign\AdvancederWorkflow\Extensions;

use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\DataObject;
use Symbiote\AdvancedWorkflow\DataObjects\WorkflowInstance;

/**
 * Adds urgency field to WorkflowActionInstance
 */
class WorkflowActionInstance_UrgencyExtension extends Extension
{
    private static $db = array(
        'IsUrgent' => 'Enum("No,Yes","No")',
        'ChangeLevel' => 'Enum("New page,Minor edit,Major edit,Re-submission","New page")',
    );



    public function onBeforeWrite()
    {
        if (!$this->owner->IsInDB()) {
            $this->owner->IsUrgent = $this->owner->Workflow()->IsUrgent;
            $this->owner->ChangeLevel = $this->owner->Workflow()->ChangeLevel;
        }
    }
    public function onAfterWrite()
    {
        // Direct DB update to avoid ORM hooks/cascading writes interfering with EditForm processing
        $workflowID  = (int) $this->owner->WorkflowID;
        if (!$workflowID) {
            return;
        }
        $table = DataObject::getSchema()->tableName(WorkflowInstance::class);
        DB::prepared_query(
            sprintf('UPDATE "%s" SET "IsUrgent" = ?, "ChangeLevel" = ? WHERE "ID" = ?', $table),
            [
                (string) $this->owner->IsUrgent,
                (string) $this->owner->ChangeLevel,
                $workflowID,
            ]
        );
    }

    public function updateWorkflowFields($fields)
    {
        $comments = $this->owner->Workflow()->CommentsSoFar();
        if ($comments) {
            $fields->push(new LiteralField('CommentsSoFar', '<div class="field textarea"><h2 class="left">Comments</h2><div class="middleColumn"><p>' . $comments . '</p></div><hr></div>'));
        }

        $fields->push(new OptionsetField('IsUrgent', 'Urgent change?', $this->owner->dbObject('IsUrgent')->enumValues()));
        $fields->push(new OptionsetField('ChangeLevel', 'Level of change', WorkflowInstance_UrgencyExtension::$change_levels));
    }

    public function canCancel($member = null)
    {
        if ($this->owner->BaseAction()->AllowCancel) {
            return $this->owner->Workflow()->getTarget()->canEdit();
        } else {
            return false;
        }
    }
}
