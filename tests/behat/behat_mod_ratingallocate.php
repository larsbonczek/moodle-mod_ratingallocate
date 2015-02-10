<?php

/**
 * Steps definitions related to mod_reallocate.
 *
 * @package mod_reallocate
 * @category test
 * @copyright 2014 Tobias Reischmann
 */
require_once (__DIR__ . '/../../../../lib/behat/behat_base.php');

use Behat\Behat\Context\Step\Given as Given, 
Behat\Gherkin\Node\TableNode as TableNode, 
Behat\Behat\Context\Step\When as When,
Behat\Mink\Exception\ExpectationException as ExpectationException;


class behat_mod_ratingallocate extends behat_base {

    /**
     * Fills the respective fields of a choice.
     *
     * @Given /^I set the values of the choice with the id (?P<choice_id>-?\d+) to:$/
     * 
     * @param integer $choiceid id of the choice
     * @param TableNode $choicedata with data for filling the choice
     */
    public function i_Set_The_Values_Of_The_Choice_With_The_Id_To($choiceid,TableNode $choicedata) {
        $result = new TableNode();
        $choicedatahash = $choicedata->getRowsHash();
        // The action depends on the field type.
        foreach ($choicedatahash as $locator => $value) {
            $result->addRow(array("id_choices_${choiceid}_$locator",$value));
        }
        return new Given("I set the following fields to these values:",$result);
    }
    
    /**
     * Delete the choice with the respective id.
     *
     * @When /^I delete the choice with the id (?P<choice_id>-?\d+)$/
     *
     * @param integer $choiceid id of the choice
     */
    public function i_Delete_The_Choice_With_The_Id($choiceid) {
        return new When('I press "id_delete_choice_'.$choiceid.'"');
    }

    /**
     * Adds a new choice for the existing rating allocation.
     *
     * @When /^I add a new choice$/
     */
    public function i_Add_A_New_Choice() {
        return new When('I press "Add new Choice"');
    }
    
    /**
     * The choice with id should be active.
     *
     * @Then /^the choice with id (?P<choice_id>-?\d+) should be active$/
     *
     * @throws ExpectationException
     * @param integer $choiceid id of the choice
     */
    public function the_Choice_should_be_active($choiceid) {
        $containernode = $this->get_selected_node("field", "id_choices_{$choiceid}_active");
        $value = $containernode->getAttribute("checked");
        if ($value == null || ($value != true)) {
            throw new ExpectationException('The choice with id "' . $choiceid .
                    '" is not active.',
                    $this->getSession());
        }
    }
    
    /**
     * The choice with id should not be active.
     *
     * @Then /^the choice with id (?P<choice_id>-?\d+) should not be active$/
     *
     * @throws ExpectationException
     * @param integer $choiceid id of the choice
     */
    public function the_Choice_should_not_be_active($choiceid) {
        $containernode = $this->get_selected_node("field", "id_choices_{$choiceid}_active");
        $value = $containernode->getAttribute("checked");
        if ($value != null && $value == true) {
            throw new ExpectationException('The choice with the id "' . $choiceid. '" is active',
                    $this->getSession());
        }
    }
    
}


class bht_ratingallocate {

    const modulename = "Ratingallocate";
}
