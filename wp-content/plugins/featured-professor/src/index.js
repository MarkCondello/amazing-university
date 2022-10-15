import "./index.scss"

import {useSelect} from "@wordpress/data"
import {useState, useEffect} from "react"
import apiFetch from "@wordpress/api-fetch"

wp.blocks.registerBlockType("ourplugin/featured-professor", {
  title: "Professor Callout",
  description: "Include a short description and link to a professor of your choice",
  icon: "welcome-learn-more",
  category: "common",
  attributes: {
    professorId: {type: String, }
  },
  edit: EditComponent,
  save: function () {
    return null
  }
})

function EditComponent(props) {
  const [thePreview, setThePreview] = useState("")

  function updateProfessorMeta(){
    const proffesorIdsForMeta = wp.data.select("core/block-editor")
      .getBlocks()
      .filter(block => {
        return block.name == "ourplugin/featured-professor"
      })
      .map(block => block.attributes.professorId)
      .filter((professorId, index, array) => { // check to see if the professorId is in the array
        return array.indexOf(professorId) == index
      })
    wp.data.dispatch("core/editor")
      .editPost({meta: {featuredprofessor: proffesorIdsForMeta}}) // this stores post data to the post_meta table
      //An inclusive list of the wp.data methods we can leverage to modify post/pages is here: https://salferrarello.com/gutenberg-js/
  }

  useEffect(() => {
    if (props.attributes.professorId) {
      updateProfessorMeta()
      async function go(){
        const response = await apiFetch({
          path: `/featuredProfessor/v1/getHTML?professorId=${props.attributes.professorId}`,
          method: "GET"
        })
        setThePreview(response)
      }
      go()
    }
  }, [props.attributes.professorId])
  
  useEffect(() => { // clean up function when unmounted to update / remove rows from the meta table
    return () => {
      updateProfessorMeta()
    }
  },[])

  const allProfessors = useSelect(select => {
    return select('core').getEntityRecords('postType', 'professor', {per_page: -1, }) // this retrieves the data for the customPost asynchonously
  })
  if (allProfessors === null) return <p>Loading...</p>
  return (
    <div className="featured-professor-wrapper">
      <div className="professor-select-container">
        <select onChange={e => props.setAttributes({professorId: e.target.value})}>
          <option value="">Select a professor</option>
          {
            allProfessors.map(professor => {
              return (
                <option value={professor.id} selected={props.attributes.professorId == professor.id}>{professor.title.rendered}</option>
              )
            })
          }
        </select>
      </div>
      <div dangerouslySetInnerHTML={{__html: thePreview}}></div>
    </div>
  )
}