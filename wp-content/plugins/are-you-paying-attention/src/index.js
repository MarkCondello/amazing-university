// Wordpress adds wp to the global scope. We can access blocks.FUNCTION from that globally accessible object
import './index.scss'
import { TextControl, Flex, FlexBlock, FlexItem, Button, Icon } from '@wordpress/components'

(function() {
  let locked = false
  wp.data.subscribe(function(){ // This fires every time the editor has changed
    console.log('Editor Change', wp.data.select("core/block-editor").getBlocks())
    const matchingBlocks = wp.data.select("core/block-editor").getBlocks().filter(block => {
      return block.name == "mrc-plugin/are-you-paying-attention" && block.attributes.correctAnswer == undefined
    })
    if (matchingBlocks.length && locked === false){
      locked = true
      wp.data.dispatch("core/editor").lockPostSaving('noanswer')
    }
    if (!matchingBlocks.length && locked ){
      locked = false
      wp.data.dispatch("core/editor").unlockPostSaving('noanswer')
    }
  })
})()

wp.blocks.registerBlockType('mrc-plugin/are-you-paying-attention', {
  title: 'Are you paying attention?',
  icon: 'smiley',
  category: 'common',
  attributes: {
    question: {type: "string", },
    answers: {type: "array", default: [""], },
    correctAnswer: {type: "string", },
  },
  edit(props){
    const handleQuestionChange = (val) => {
      props.setAttributes({question: val})
    }
    const deleteAnswer = (indexToDelete) => {
      if(props.attributes.answers[indexToDelete] == props.attributes.correctAnswer) {
        props.setAttributes({correctAnswer: undefined})
      }
      const newAnswers = props.attributes.answers.filter((el, index) => index !== indexToDelete)
      props.setAttributes({answers: newAnswers})
    }
    const handleCorrectAnswerClick = (answer => {
      props.setAttributes({correctAnswer: answer})
    })
    return (
      <div className="paying-attention-edit-block">
        <TextControl label="Question:" style={{fontSize: '20px'}} value={props.attributes.question} onChange={handleQuestionChange} />
        <p style={{fontSize: '13px', margin: "20px 8px 0 0"}}>Answers</p>
        {props.attributes.answers.map((answer, index) => {
          return <Flex>
            <FlexBlock>
              <TextControl
                value={answer}
                autoFocus={answer === undefined}
                onChange={ newVal => {
                  const newAnswers = props.attributes.answers.concat([])
                  newAnswers[index] = newVal
                  props.setAttributes({answers: newAnswers})
                }}
              />
            </FlexBlock>
            <FlexItem>
              <Button>
                <Icon
                  icon={answer === props.attributes.correctAnswer ? "star-filled" : "star-empty"}className="star" onClick={()=>handleCorrectAnswerClick(answer)}
                />
              </Button>
            </FlexItem>
            <FlexItem>
              <Button className="delete" isLink onClick={()=> deleteAnswer(index)}
              >Delete</Button>
            </FlexItem>
          </Flex>
        })}
        <Button isPrimary onClick={() => {
          const newAnswers = props.attributes.answers.concat([undefined])
          props.setAttributes({answers: newAnswers})
        }}>Add another answer</Button>
      </div>
    )
  },
  save(props){
    return null
  },

})
