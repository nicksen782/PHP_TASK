<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * StudentPerformance
 *
 * @ORM\Table(name="student_performance")
 * @ORM\Entity
 */
class StudentPerformance
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="group_name", type="string", length=50, nullable=true)
     */
    private $groupName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="subject", type="string", length=50, nullable=true)
     */
    private $subject;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var int|null
     *
     * @ORM\Column(name="assessment_score", type="integer", nullable=true)
     */
    private $assessmentScore;


}
