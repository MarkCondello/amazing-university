import React, { useState, useEffect }  from 'react'
import ReactDOM from 'react-dom'
import './frontend.scss'

const payingAttentionBlocks = document.querySelectorAll('.paying-attention-block')

payingAttentionBlocks.forEach(block => {
  const data = JSON.parse(block.querySelector('pre').innerHTML)
  ReactDOM.render(<Quiz {...data}/>, block)
  block.classList.remove('paying-attention-block')
})

function Quiz(props) {
  console.log('Props', props)
  const [isCorrect, setIsCorrect] = useState(undefined)
  const [isCorrectDelayed, setIsCorrectDelayed] = useState(undefined)
  useEffect(()=>{
    if (isCorrect === false) {
      setTimeout(()=>{
        setIsCorrect(undefined)
      }, 2600)
    }
    if (isCorrect) {
      setTimeout(()=>{
        setIsCorrectDelayed(true)
      }, 1000)
    }
  }, [isCorrect]) // checks for changes to isCorrect state item
  const handleAnswerClick = (answer) => {
    if (answer === props.correctAnswer){
      console.log("correct")
      setIsCorrect(true)
    } else {
      console.log('wrong answer')
      setIsCorrect(false)
    }
  }
  return (
    <div className="paying-attention-frontend" style={{backgroundColor: props.backgroundColor}}>
      <p>{props.question}</p>
      <ul>
      { props.answers.map(answer =>
        <li className={(isCorrectDelayed === true && answer == props.correctAnswer && "no-click") + 
          (isCorrectDelayed === true && answer != props.correctAnswer && " fade-incorrect")}
          onClick={isCorrect === true ? undefined : () => handleAnswerClick(answer)}>
          {isCorrectDelayed === true && answer == props.correctAnswer && (
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" className="bi bi-check2" viewBox="0 0 16 16">
            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
          </svg>)}
          {isCorrectDelayed === true && answer != props.correctAnswer && (
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" className="bi bi-x" viewBox="0 0 16 16">
            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
          </svg>)}
          {answer}
        </li>
      )}
      </ul>
    {isCorrect !== undefined && (
      isCorrect === true ? (
      <div className="correct-message  correct-message--visible">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" className="bi bi-emoji-smile" viewBox="0 0 16 16">
          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
          <path d="M4.285 9.567a.5.5 0 0 1 .683.183A3.498 3.498 0 0 0 8 11.5a3.498 3.498 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.498 4.498 0 0 1 8 12.5a4.498 4.498 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5z"/>
        </svg>
        <p>That is correct!</p>
      </div>
      ) : (
      <div className="incorrect-message correct-message--visible">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" className="bi bi-emoji-frown" viewBox="0 0 16 16">
          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
          <path d="M4.285 12.433a.5.5 0 0 0 .683-.183A3.498 3.498 0 0 1 8 10.5c1.295 0 2.426.703 3.032 1.75a.5.5 0 0 0 .866-.5A4.498 4.498 0 0 0 8 9.5a4.5 4.5 0 0 0-3.898 2.25.5.5 0 0 0 .183.683zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5z"/>
        </svg>
        <p>That is wrong!</p>
      </div>
      ))}
    </div>
  )
}